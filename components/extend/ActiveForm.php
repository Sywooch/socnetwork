<?php

namespace app\components\extend;

use yii\bootstrap\ActiveForm as BaseActiveForm;
use app\components\extend\Html;
use yii;

class ActiveForm extends BaseActiveForm
{

    public $fieldClass = 'app\components\extend\ActiveField';

    public static function begin($config = array())
    {
        return parent::begin(self::defaultConfig($config));
    }

    public static function defaultConfig($config = [])
    {
        return array_merge([
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'options' => [
                'enctype' => 'multipart/form-data',
                'data' => [
                    'pjax' => yii::$app->controller->isPjaxAction
                ]
            ]
                ], $config);
    }

}
