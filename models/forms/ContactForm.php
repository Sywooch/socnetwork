<?php

namespace app\models\forms;

use Yii;
use app\components\extend\Model;
use app\components\helper\Helper;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{

    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        $rules = [
            // name, email, subject and body are required
                [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
                // verifyCode needs to be entered correctly
        ];

        if (Helper::data()->getSession('x')) {
            $rules[] = ['verifyCode', 'captcha'];
        }

        return $rules;
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => yii::$app->l->t('verification code'),
            'name' => yii::$app->l->t('name'),
            'email' => yii::$app->l->t('email'),
            'subject' => yii::$app->l->t('subject'),
            'body' => yii::$app->l->t('content'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Helper::email()->send(Helper::data()->getParam('adminEmail'), $this->subject, ['view' => 'contact', 'params' => ['model' => $this]], [
                $this->email => yii::$app->name
            ]);
            return true;
        } else {
            return false;
        }
    }

}
