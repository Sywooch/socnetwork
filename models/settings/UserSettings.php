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
        ];

        return $this->settings;
    }

}
