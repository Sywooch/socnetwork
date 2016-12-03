<?php

namespace app\models;

use Yii;
use app\components\helper\Helper;
use app\components\extend\Html;

/**
 * This is the model class for table "{{%file_destination}}".
 *
 * @property    integer     $file_name
 * @property    string      $destination
 */
class FileDestination extends \app\components\extend\Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%file_destination}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge([
            [['file_name', 'destination'], 'required'],
        ]);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'file_name' => yii::$app->l->t('file'),
            'destination' => yii::$app->l->t('destination'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['file_name', 'destination'];
    }

}