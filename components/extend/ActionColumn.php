<?php

namespace app\components\extend;

use Yii;
use app\components\extend\Html;
use yii\grid\ActionColumn as BaseActionColumn;

class ActionColumn extends BaseActionColumn
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => yii::$app->controller->isPjaxAction,
                        ], $this->buttonOptions);
                return Html::a(Html::icon('eye') . '----', $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => yii::$app->controller->isPjaxAction,
                        ], $this->buttonOptions);
                return Html::a(Html::icon('pencil'), $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => yii::$app->controller->isPjaxAction,
                        ], $this->buttonOptions);
                return Html::a(Html::icon('delete'), $url, $options);
            };
        }
    }

}
