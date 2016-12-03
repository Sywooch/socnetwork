<?php

use \app\modules\admin\components\widgets\rbac\manageUserRoles\manageUserRolesAssets;
use \app\components\extend\Html;
use \yii\rbac\Item;

manageUserRolesAssets::register($this);
?>
<div class="rbac-page-container">
    <?php
    $tmp = '';
    if (array_key_exists($type, $rbac->items)):
        foreach ($rbac->items[$type] as $k => $v) {
            $tmp.= $this->render('_item', [
                'rbac' => $rbac,
                'item' => $v,
                'tmp' => $tmp,
            ]);
        }
    endif;
    $search = Html::tag('div', $this->render('_js_filter', [
                        'type' => $type,
                    ]), ['class' => 'list-group-item head']);
    ?>

    <?php
    $result = Html::tag('div', $search . ($tmp !== '' ? Html::tag('div', $tmp, ['class' => 'rbac-items-container']) : yii::$app->l->t('no results')), [
                'class' => 'list-group',
                'data' => [
                    'item-list-type' => $type
                ]
    ]);
    ?>

    <?= $this->render('_item_modal', ['content' => $result]); ?>
</div>