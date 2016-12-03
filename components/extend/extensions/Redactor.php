<?php

namespace app\components\extend\extensions;

use Yii;
use vova07\imperavi\Widget as Imperavi;

class Redactor extends Imperavi
{
    /**
     * 
     * @param array $customSettings
     * @param array $customOptions
     * @return array
     */
    public static function getDefaultSettings($customSettings = [], $customOptions = [])
    {
        return [
            'settings' => array_merge([
                'lang' => (yii::$app->language != 'en' ? yii::$app->language : 'ru'),
                'minHeight' => 300,
                'plugins' => [
                    'clips',
                    'fullscreen'
                ]
                    ], $customSettings),
            'options' => array_merge([
                'style' => 'display:none'
                    ], $customOptions)
        ];
    }

}