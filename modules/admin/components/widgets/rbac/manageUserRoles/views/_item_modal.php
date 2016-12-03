<?php

use yii\bootstrap\Modal;
use app\components\extend\Html;
use yii\bootstrap\Tabs;

Modal::begin([
    'id' => 'manage-user-roles-modal',
    'size' => Modal::SIZE_DEFAULT,
    'header' => Html::tag('h2', yii::$app->l->t('manage roles of {user}', [
                'user' => Html::tag('span', '', [
                    'class' => 'user-name'
                ])
            ]), ['class' => 'modal-header-h']),
]);
?>
<div class="row">
    <?=
    Html::tag('div', $content, [
        'class' => 'col-lg-12'
    ]);
    ?>
</div>
<div class="clearfix"></div>

<?php
Modal::end();
