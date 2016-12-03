<?php

use app\components\extend\Html;
?>
<div class="jFiler-info-container input-group <?= $uploader->template ?>" style="margin-bottom: 1px">
    <?php
    echo Html::textInput(null, null, [
        'placeholder' => '-',
        'class' => 'form-control fiInputInfoIndicator',
        'readonly' => true,
        'data' => [
            'counter' => 0,
            'text' => yii::$app->l->t('selected files', ['update' => false]),
        ]
    ]);
    ?>
    <?php
    echo Html::tag('div', Html::ico('plus') . ' ' . yii::$app->l->t('choose files'), [
        'class' => 'input-group-btn btn btn-primary browse-button',
    ]);
    ?>
</div>