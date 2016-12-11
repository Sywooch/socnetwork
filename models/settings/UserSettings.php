<?php

/**
 * Description of SeoBehavior
 *
 * @author postolachiserghei
 */

namespace app\models\settings;

use yii;
use app\models\Settings;
use app\models\behaviors\SettingsBehavior;
use app\components\extend\Html;
use app\components\extend\Model as BaseModel;
use app\components\helper\Helper;
use app\models\User;

class UserSettings extends SettingsBehavior
{

    /**
     * @inheritdoc
     */
    public function events()
    {
        return[
            BaseModel::EVENT_SETTINGS_ASIGN => 'setSettingsForBehavior'
        ];
    }

    /*
     * set settings for owner model
     */

    public function setSettingsForBehavior()
    {
        $this->settings = [
            'default_user_role' => [
                'before' => Html::tag('h3', yii::$app->l->t('user settings')),
                'label' => yii::$app->l->t('default user role'),
                'value' => null,
                'field' => Settings::FIELD_CHECKBOX_LIST,
                'items' => Helper::user()->identity()->getAvailableRoles(),
            ],
            'pay_to_referals_amount' => [
                'before' => '<div class="clearfix"></div>' . Html::tag('h3', yii::$app->l->t('payment settings')),
                'label' => yii::$app->l->t('amount to pay to referrals'),
                'value' => 150,
                'field' => Settings::FIELD_TEXT,
            ],
            'pay_to_administration_amount' => [
                'label' => yii::$app->l->t('amount to pay to administration'),
                'value' => 500,
                'field' => Settings::FIELD_TEXT,
            ],
            'pay_on_signup_amount' => [
                'label' => yii::$app->l->t('cost of signup'),
                'value' => 2000,
                'field' => Settings::FIELD_TEXT,
            ],
            'admin_payment_receiver_id' => [
                'label' => yii::$app->l->t('user id that will receive paiments as {appname} admin', [
                    'appname' => yii::$app->name
                ]),
                'value' => (($user = User::findByUsername('admin')) ? $user->primaryKey : 0),
                'field' => Settings::FIELD_TEXT,
                'after' => '<div class="clearfix"></div><br/>'
            ],
            'system_currency' => [
                'label' => yii::$app->l->t('system currency') . '&nbsp;',
                'field' => Settings::FIELD_RADIO_LIST,
                'value' => 'USD',
                'items' => [
                    'USD' => 'USD',
                    'EUR' => 'EUR',
                    'RUR' => 'RUR',
                ]
            ],
        ];

        return $this->settings;
    }

}
