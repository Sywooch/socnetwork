<?php

use \yii\rbac\Item;
use app\components\extend\Html;
?>
<div class="row">
    <div class="col-md-11">
        <div class="input-group input-group-sm w100">
            <?=
            Html::input('text', 'rbac-filter', '', [
                'placeholder' => yii::$app->l->t('filter', ['update' => false]),
                'class' => 'form-control rbac-filter-text-input ' . ($type === Item::TYPE_PERMISSION ? 'w30' : 'w100'),
                'aria-describedby' => 'sizing-addon3',
                'onkeyup' => 'filterRbacItems($(this)' . ($type === Item::TYPE_PERMISSION ? ',true' : '') . ')',
                'data' => [
                    'type' => $type
                ],
            ]);
            ?>
            <?=
            ($type === Item::TYPE_PERMISSION ? Html::dropDownList('rbac-filter-drop-down', '', $copartments, [
                        'class' => 'form-control rbac-filter-section w60',
                        'onchange' => 'filterRbacItems($(this),true)',
                        'prompt' => yii::$app->l->t('all sections', ['update' => false]),
                        'data' => [
                            'type' => $type
                        ],
                    ]) : '');
            ?>
        </div>
    </div>

    <div class="col-md-1">
        <div class="btn-group pull-right">
            <?=
            Html::a(Html::ico('plus'), '#', [
                'title' => yii::$app->l->t('create', ['update' => false]),
                'onclick' => 'showRbacModalForm(null,' . $type . ',null);return false;'
            ]);
            ?>
        </div>
    </div>
</div>