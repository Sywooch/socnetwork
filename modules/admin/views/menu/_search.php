<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\MenuSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'parent') ?>

    <?= $form->field($model, 'order') ?>

    <div class="form-group">
        <?= Html::submitButton(yii::$app->l->t('Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(yii::$app->l->t('Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
