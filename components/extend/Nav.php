<?php

namespace app\components\extend;

use yii;
use app\components\extend\Dropdown;
use app\components\extend\Html;
use app\components\extend\ArrayHelper;
use yii\base\InvalidConfigException;

class Nav extends \yii\bootstrap\Nav
{

    public $encodeLabels = false;
    static $actions = [
        'index' => ['create', 'delete_all'],
        'create' => ['index'],
        'view' => ['index', 'create', 'update', 'delete'],
        'update' => ['index', 'create', 'view', 'delete'],
    ];
    static $icons = ['index' => 'list', 'create' => 'plus', 'view' => 'eye', 'update' => 'pencil', 'delete' => 'trash'];

    /**
     * @param object $model
     * @param array $additionalItems
     * @param array $exclude
     * @param boolean $withParams
     * @return array
     */
    public static function CrudActions($model, $additionalItems = [], $exclude = [], $withParams = false)
    {
        $action = yii::$app->controller->action->id;
        $data = [];
        $ar = [];
        if (array_key_exists($action, self::$actions)) {
            foreach (self::$actions[$action] as $k => $a) {
                $options = ['class' => 'btn btn-default', 'data' => $data, 'title' => yii::$app->l->t($a == 'index' ? 'list' : $a, ['update' => false])];
                if ($a == 'delete') {
                    $pjax = yii::$app->controller->isPjaxAction ? "Common.pjaxRedirect('" . Url::to(['index']) . "');" : 'window.location.replace("' . Url::to(['index']) . '");';
                    $options['onclick'] = "Common.deleteRecordsFromArray(null,'" . Url::to(['delete']) . "','" . yii::$app->l->t('аre you sure you want to delete this item?') . "',['$model->primaryKey'],function(){" . $pjax . "});return false;";
                }
                if ($exclude && is_array($exclude) && in_array($a, $exclude, true)) {
                    continue;
                }
                $get = yii::$app->request->get();
                $ar[] = $a != 'delete_all' ? [
                    'visible' => yii::$app->user->can(yii::$app->controller->id . '-' . $a),
                    'label' => (array_key_exists($a, self::$icons) ? Html::ico(self::$icons[$a]) : yii::$app->l->t($a == 'index' ? 'list' : $a)),
                    'url' => ((!$model->isNewRecord && $a != 'index' && $a != 'create') ? [$a, 'id' => $model->primaryKey] + $get : [$a] + ($withParams ? $get : [])),
                    'linkOptions' => $options,
                        ] : self::crudActionsDeleteAllButton($model, $a);
            }
        }
        if (count($additionalItems) > 0)
            foreach ($additionalItems as $ai)
                $ar[] = $ai;
        if ($model->getBehavior('settings')) {
            $modelShortName = (new \ReflectionClass($model))->getShortName();
            $ar[] = [
                'label' => Html::ico('cogs'),
                'visible' => yii::$app->user->can(strtolower($modelShortName) . '-settings'),
                'linkOptions' => ['class' => 'btn btn-default', 'title' => yii::$app->l->t('settings', ['update' => false])],
                'url' => ['/admin/settings/update', 'm' => $modelShortName]
            ];
        }
        return $ar;
    }

    public static function crudActionsDeleteAllButton($model, $a)
    {

        return [
            'visible' => yii::$app->user->can(yii::$app->controller->id . '-delete'),
            'label' => Html::ico('trash') . ' ' . Html::ico('long-arrow-right') . ' ' . Html::ico('check-square-o'),
            'url' => ['delete'],
            'linkOptions' => [
                'class' => 'btn btn-default delete-all-button',
                'title' => yii::$app->l->t('delete selected items', ['update' => false]),
                'onclick' => "Common.deleteRecordsFromArray('.selectGridViewCheckBox','" .
                Url::toRoute(['delete']) .
                "','" . yii::$app->l->t('delete selected items') . " ?');return false;"
            ],
        ];
    }

    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        if ($items !== null) {
            $linkOptions['data-toggle'] = 'dropdown';
            Html::addCssClass($options, ['widget' => 'dropdown']);
            Html::addCssClass($linkOptions, ['widget' => 'dropdown-toggle']);
            if ($this->dropDownCaret !== '') {
                $label .= ' ' . $this->dropDownCaret;
            }
            if (is_array($items)) {
                if ($this->activateItems) {
                    $items = $this->isChildActive($items, $active);
                }
                $items = $this->renderDropdown($items, $item);
            }
        }

        if ($this->activateItems && $active) {
            Html::addCssClass($options, 'active');
        }

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }

    protected function renderDropdown($items, $parentItem)
    {
        return Dropdown::widget([
                    'options' => ArrayHelper::getValue($parentItem, 'dropDownOptions', []),
                    'items' => $items,
                    'encodeLabels' => $this->encodeLabels,
                    'clientOptions' => false,
                    'view' => $this->getView(),
        ]);
    }

}
