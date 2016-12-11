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
use app\components\extend\Model as BaseModel;
use app\components\helper\Helper;
use app\components\extend\Html;

class BaseSettings extends SettingsBehavior
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
        $request = yii::$app->request;
        $this->settings = [
            'tld' => [
                'before' => Html::tag('h4', yii::$app->l->t('default site settings')),
                'label' => yii::$app->l->t('top level domain'),
                'field' => Settings::FIELD_TEXT,
                'value' => str_replace('http://', '', str_replace('https://', '', is_a(Yii::$app, 'yii\web\Application') ? $request->hostInfo : 'localhost'))
            ],
            'adminEmail' => [
                'label' => yii::$app->l->t('admin email'),
                'field' => Settings::FIELD_TEXT,
                'value' => 'gaftonsifon@yandex.com',
                'after' => '<div class="clearfix"></div><br/>'
            ],
            'usePjaxAdmin' => [
                'before' => Html::tag('h4', yii::$app->l->t('pjax settings')),
                'containerOptions' => ['class' => 'col-md-3'],
                'label' => yii::$app->l->t('use pjax for admin panel') . '&nbsp;',
                'field' => Settings::FIELD_CHECKBOX,
                'value' => 0
            ],
            'usePjaxFrontend' => [
                'label' => yii::$app->l->t('use pjax for frontend') . '&nbsp;',
                'field' => Settings::FIELD_CHECKBOX,
                'containerOptions' => ['class' => 'col-md-3'],
                'value' => 0,
            ],
        ];
        return $this->settings;
    }

}
