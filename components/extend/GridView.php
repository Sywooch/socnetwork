<?php

namespace app\components\extend;

use yii;
use app\components\extend\Url;
use \yii\grid\GridView as BaseGridView;

class GridView extends BaseGridView
{

    public $dataColumnClass = '\app\components\extend\DataColumn';
    static $actions = ['index', 'create', 'view', 'update', 'delete'];
    static $icons = ['index' => 'list', 'create' => 'plus', 'view' => 'eye', 'update' => 'pencil', 'delete' => 'trash'];

    /**
     * @param object $model
     * @param array $additionalItems
     * @return array
     */
    public static function DefaultActions($model, $additionalItems = [])
    {
        $ar = [];
        foreach (self::$actions as $a) {
            $ar[$a] = function($url, $model, $key) use ($a) {
                $options = [
                    'title' => yii::$app->l->t($a, ['update' => false]),
                    'data' => ['pjax' => yii::$app->controller->isPjaxAction]
                ];
                if ($a == 'delete') {
                    $delete = "Common.deleteRecordsFromArray(null,'" . Url::to(['delete']) . "','" . yii::$app->l->t('аre you sure you want to delete this item?') . "',['$model->primaryKey']);return false;";
                    $options['onclick'] = $delete;
                    $url = '#';
                }
                $button = Html::a((array_key_exists($a, self::$icons) ? Html::ico(self::$icons[$a]) : yii::$app->l->t($a == 'index' ? 'list' : $a)), $url, $options);
                return yii::$app->user->can(yii::$app->controller->id . '-' . $a) ? $button : '';
            };
        }

        return ($ar + $additionalItems);
    }

    public static function checkboxColumn($options = [])
    {
        $checkBoxOptions = function($model, $key, $index, $column) use($options) {
            $options['value'] = $model->primaryKey;
            Html::addCssClass($options, 'selectGridViewCheckBox');
            return $options;
        };
        return [
            'class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => $checkBoxOptions
        ];
    }

    /**
     * 
     * @param array $options
     * @return array
     */
    public static function defaultOptionsForFilterDropdown($options = [])
    {

        return array_merge([
            'prompt' => yii::$app->l->t('all options', ['update' => false]), 'class' => 'form-control'
                ], $options);
    }

}
