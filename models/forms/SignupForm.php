<?php

namespace app\models\forms;

use yii;
use app\components\extend\Html;
use app\models\User;
use app\components\extend\Model;
use app\models\behaviors\FileSaveBehavior;
use app\components\extend\Url;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $user;
    public $username;
    public $email;
    public $skype;
    public $password;
    public $first_name;
    public $last_name;
    public $country;
    public $city;
    public $gender;
    public $about;
    public $agree;
    public $payment;

    const PAYMENT_TYPE_SUBSCRIBER_PERIOD = 'subscriber_period';
    const PAYMENT_TYPE_CONTRIBUTION_AMMOUNT = 'contribution_ammount';

    /**
     * 
     * @param mixed $gender
     */
    public function getGenderLabels($gender = null)
    {
        return $this->user->getGenderLabels($gender);
    }

    /**
     * 
     * @param mixed $type
     */
    public function getPaymentTypeLabels($type = null)
    {
        $ar = [
            self::PAYMENT_TYPE_SUBSCRIBER_PERIOD => yii::$app->l->t('Subscriber period'),
            self::PAYMENT_TYPE_CONTRIBUTION_AMMOUNT => yii::$app->l->t('The amount of contribution'),
        ];

        return $type !== null ? $ar[$type] : $ar;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $init = parent::init();
        $this->user = new User();
        return $init;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                ['username', 'filter', 'filter' => 'trim'],
                [['password', 'agree', 'first_name', 'last_name', 'email'], 'required'],
                ['username', 'unique', 'targetClass' => 'app\models\User'],
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['agree', 'checkAgreement'],
                ['email', 'unique', 'targetClass' => 'app\models\User'],
                ['password', 'required'],
                ['password', 'string', 'min' => 6],
        ];
    }

    public function checkAgreement()
    {
        if ($this->agree != 1) {
            $this->addError('agree', yii::$app->l->t('you need to agree with terms and conditions!'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge([
            'agree' => Html::a(yii::$app->l->t('agree with terms and conditions'), Url::to([
                        '/terms-and-conditions'
            ])),
                ], (new User())->attributeLabels());
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        $user = $this->user;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->country = $this->country;
        $user->city = $this->city;
        $user->gender = $this->gender;
        $user->about = $this->about;
        if ($this->validate() && $user->validate()) {
            $user->save();
            return $user;
        }
        return null;
    }

}
