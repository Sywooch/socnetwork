<?php

use \app\modules\admin\components\widgets\rbac\manager\rbacManagerAssets;
use \app\components\extend\Html;
use \yii\rbac\Item;

rbacManagerAssets::register($this);
?>
<div class="rbac-page-container">
    <?php
    $tmp = '';
    $copartments = [];
    if (array_key_exists($type, $rbac->items)):
        foreach ($rbac->items[$type] as $k => $v) {
            if (Item::TYPE_PERMISSION == $type) {
                $item = explode('-', $v->name);
                if (count($item) > 1) {
                    $section = yii::$app->l->t($item[0] . ' side', ['update' => false]);
                    $copartments[$section][$item[0] . '-' . $item[1]] = yii::$app->l->t($item[1], ['update' => false]) . ' - (' . $section . ')';
                }
            }
            $tmp.= $this->render('_item', [
                'rbac' => $rbac,
                'item' => $v,
                'tmp' => $tmp,
            ]);
        }
    endif;
    $search = Html::tag('div', $this->render('_js_filter', [
                        'type' => $type,
                        'copartments' => $copartments,
                    ]), ['class' => 'list-group-item head']);
    ?>

    <?=
    Html::tag('div', $search . ($tmp !== '' ? Html::tag('div', $tmp, ['class' => 'rbac-items-container']) : yii::$app->l->t('no results')), [
        'class' => 'list-group',
        'data' => [
            'item-list-type' => $type
        ]
    ]);
    ?>
</div>