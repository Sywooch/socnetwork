<?php
/**
 * Description of SeoBehavior
 *
 * @author postolachiserghei
 */

namespace app\models\settings;

use Yii;
use app\models\Settings;
use app\models\behaviors\SettingsBehavior;
use app\models\File;
use app\components\extend\Html;
use \app\components\extend\Model as BaseModel;

class FileSettings extends SettingsBehavior
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
            'transfer_to_ftp' => [
                'before' => Html::tag('h3', yii::$app->l->t('Ftp settings')),
                'label' => yii::$app->l->t('transfer files to ftp server'),
                'value' => File::LOCATION_LOCAL,
                'field' => Settings::FIELD_DROPDOWN,
                'items' => [
                    File::LOCATION_FTP => yii::$app->l->t('yes'),
                    File::LOCATION_LOCAL => yii::$app->l->t('no'),
                ],
                'containerOptions' => ['class' => 'col-md-2'],
            ],
            'ftp_host' => [
                'label' => yii::$app->l->t('ftp server host'),
                'value' => 'screen-cloud.it-init.ru',
                'field' => Settings::FIELD_TEXT,
                'containerOptions' => ['class' => 'col-md-2'],
            ],
            'ftp_username' => [
                'label' => yii::$app->l->t('username'),
                'value' => 'admin_f',
                'field' => Settings::FIELD_TEXT,
                'containerOptions' => ['class' => 'col-md-2'],
            ],
            'ftp_password' => [
                'label' => yii::$app->l->t('password'),
                'value' => '123qwe',
                'field' => Settings::FIELD_PASSWORD,
                'containerOptions' => ['class' => 'col-md-2'],
            ],
            'ftp_dir' => [
                'label' => yii::$app->l->t('directory'),
                'value' => 'public_html',
                'field' => Settings::FIELD_TEXT,
                'containerOptions' => ['class' => 'col-md-2'],
            ],
            'ftp_ssl' => [
                'label' => yii::$app->l->t('use ssl'),
                'value' => 'no',
                'field' => Settings::FIELD_DROPDOWN,
                'items' => [
                    'no' => yii::$app->l->t('no'),
                    'yes' => yii::$app->l->t('yes'),
                ],
                'containerOptions' => ['class' => 'col-md-1'],
            ],
            'ftp_port' => [
                'label' => yii::$app->l->t('port'),
                'value' => '21',
                'field' => Settings::FIELD_TEXT,
                'containerOptions' => ['class' => 'col-md-1'],
                'after' => '<div class="clearfix"></div><hr/>' . Html::tag('h3', yii::$app->l->t('settings for image files'))
            ],
        ];

        foreach (File::getImageSizes() as $k => $v) {
            $this->settings['imageWidth' . $k] = [
                'label' => yii::$app->l->t('width for {image-size} image (px)', ['image-size' => $v]),
                'value' => '1024',
                'field' => Settings::FIELD_TEXT,
                'containerOptions' => ['class' => 'col-md-6'],
            ];
            $this->settings['imageHeight' . $k] = [
                'label' => yii::$app->l->t('height for {image-size} image (px)', ['image-size' => $v]),
                'value' => '850',
                'field' => Settings::FIELD_TEXT,
                'containerOptions' => ['class' => 'col-md-6'],
                'after' => '<div class="clearfix"></div><br/>'
            ];
        }

        return $this->settings;
    }

}