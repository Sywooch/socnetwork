<?php

use \yii\rbac\Item;
use app\components\extend\Html;
?>
<div class="row">
    <div class="col-md-12">
        <div class="input-group input-group-sm w100">
            <?=
            Html::input('text', 'rbac-filter', '', [
                'placeholder' => yii::$app->l->t('filter', ['update' => false]),
                'class' => 'form-control rbac-filter-text-input w100',
                'aria-describedby' => 'sizing-addon3',
                'onkeyup' => 'filterUserRoleItems($(this));return false;',
                'data' => [
                    'type' => $type
                ],
            ]);
            ?>
        </div>
    </div>
</div>