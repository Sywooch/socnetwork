<?php

namespace app\components\extend;

use yii;

class Pjax extends \yii\widgets\Pjax
{

    const PAGE_CONTAINER = 'pjax-page-container';

    /**
     * default parameters
     * @param array $params
     * @return array
     */
    public static function DefaultParams($params = [])
    {
        return array_merge([
            'enablePushState' => true,
            'enableReplaceState' => false,
            'id' => 'manage_grid_view',
            'timeout' => (60/* seconds */ * 1000/* milliseconds */),
            'scrollTo' => false,
            'options' => [
                'class' => 'manage_grid_view',
            ],
            'clientOptions' => [
                "pushRedirect" => true,
                "replaceRedirect" => false,
                "cache" => false,
                "timeout" => false,
            ]
                ], $params);
    }

    /**
     * 
     * @param array $config
     * @return type
     */
    public static function begin($config = array())
    {
        if (yii::$app->controller->isPjaxAction) {
            $config['id'] = self::PAGE_CONTAINER;
            Html::addCssClass($config['options'], 'pjax-app');
        }
        $options = self::DefaultParams($config);
        $options['options']['data'] = array_merge($options['clientOptions'], (@$config['options'] && @$config['options']['data']) ? $config['options']['data'] : []);
        $options['options']['data']['timeout'] = $options['timeout'];
        return parent::begin($options);
    }

}
