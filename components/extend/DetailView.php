<?php

namespace app\components\extend;

use yii;
use yii\helpers\ArrayHelper;
use app\components\extend\Html;

class DetailView extends \yii\widgets\DetailView
{
    public $format;

    public function init()
    {
        parent::init();
        $this->format = yii::$app->l->liveEditT ? 'html' : 'text';
    }

    /**
     * @param object $model
     * @param array $additionalItems
     * @param array/boolean $ignore
     * @return array
     */
    public static function DefaultAttributes($model, $additionalItems = [], $ignore = false)
    {
        $attrs = $model->attributeLabels();
        if (is_array($ignore)) {
            foreach ($ignore as $i) {
                if ($i && array_key_exists($i, $attrs)) {
                    unset($attrs[$i]);
                }
            }
        }
        $a = [];
        if (array_key_exists('labels', $additionalItems)) {
            foreach ($additionalItems['labels'] as $k => $v) {
                $a[] = $v;
            }
            unset($additionalItems['labels']);
        }
        foreach ($attrs as $k => $v) {
            $value = $model->{$k};
            !array_key_exists($k, $additionalItems) ? $a[] = ['label' => $model->getAttributeLabel($k), 'value' => $value] : $a[$k] = $additionalItems[$k];
        }


        return $a;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $rows = [];
        $i = 0;
        foreach ($this->attributes as $attribute) {
            if (!array_key_exists('format', $attribute) || $attribute['format'] == 'text') {
                $attribute['format'] = $this->format;
            }
            $rows[] = $this->renderAttribute($attribute, $i++);
        }

        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'table');
        echo Html::tag($tag, implode("\n", $rows), $options);
    }

}