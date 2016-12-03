<?php

namespace app\models\forms;

use Yii;
use app\models\User;
use app\components\extend\Model;
use \app\components\helper\EmailHelper;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => yii::$app->l->t('email'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail($resetLink)
    {
        /* @var $user User */
        $user = User::findOne([
                    'status' => User::STATUS_ACTIVE,
                    'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            if ($user->save()) {
                $subject = yii::$app->l->t('password reset');
                $render = ['view' => 'passwordResetToken', 'params' => ['user' => $user, 'subject' => $subject, 'resetLink' => $resetLink]];
                return EmailHelper::send($this->email, $subject, $render, [\Yii::$app->params['supportEmail'] => \Yii::$app->name]);
            }
        }

        return false;
    }

}