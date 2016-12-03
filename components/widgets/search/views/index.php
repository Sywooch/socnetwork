<?php

use app\components\extend\Html;
use app\components\extend\Url;
use app\components\extend\ActiveForm;
use app\components\widgets\search\SearchWidgetAssets;

SearchWidgetAssets::register($this);
?>

<?=
Html::beginForm(Url::to(['/search/index']), 'get', ['class' => 'col-md-2 col-sm-3', 'style' => 'margin-top: 15px;', 'data' => [
        'pjax' => true
]]);
?>
<div class="input-group">
        <?= Html::input('text', 'Search', is_string($post) ? $post : '', ['class' => 'form-control', 'placeholder' => yii::$app->l->t('search', ['update' => false])]) ?>
    <span class="input-group-btn fs-1">
<?= Html::submitButton(Html::ico('search'), ['class' => 'btn btn-default']) ?>
    </span>
</div>

<?= Html::endForm(); ?>