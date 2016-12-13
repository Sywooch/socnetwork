<?php

namespace app\components\extend;

use yii;
use kartik\icons\Icon;

class Html extends yii\bootstrap\Html
{

    public static function icons()
    {
        $i = new Icon();
        yii::$app->params['icon-framework'] = 'fa';
        $i->map(yii::$app->view);
        return $i;
    }

    /**
     * @param string $name
     * @param array $options
     * @return html
     */
    public static function ico($name, $options = [], $tag = 'i')
    {
        return static::icons()->show($name, $options, null, true, $tag);
    }

    /**
     * @inheritdoc
     */
    public static function tag($name, $content = '', $options = array())
    {
        return parent::tag($name, static::checkIcon($content, $options), $options);
    }

    /**
     * 
     * @param array $item
     * @return string (returns html tag with icon class)
     */
    public static function checkIcon($content, $options)
    {
        $tmp = '';
        if (isset($options['icon'])) {
            $tmp .= Html::ico($options['icon'], (isset($options['iconOptions']) ? $options['iconOptions'] : []));
        }
        return $tmp . $content;
    }

    /**
     * @inheritdoc
     */
    public static function beginForm($action = '', $method = 'post', $options = array())
    {
        $defaultOptions = [
            'data' => [
                'pjax' => yii::$app->controller->isPjaxAction
            ]
        ];
        $options['data'] = @$options['data'] ? array_merge($defaultOptions['data'], $options['data']) : $defaultOptions['data'];
        return parent::beginForm($action, $method, $options);
    }

    /**
     * @inheritdoc
     */
    public static function img($src, $options = array())
    {
        self::addCssClass($options, 'img');
        if (array_key_exists('wrapIntoBg', $options)) {
            $img = parent::img($src, ['style' => 'visibility:hidden;position:relative']);
            $options['style'] = 'background: url("' . $src . '");background-repeat: no-repeat;background-position: 50%;height: 100%;width: 100%;display: inline-block;background-size: cover;';
            $wraped = self::tag('div', $img, $options);
            return $wraped;
        } else {
            return parent::img($src, $options);
        }
    }

}
