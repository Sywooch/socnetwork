<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Seo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seo-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= Html::tag('div', $model->getTButtons(), ['class' => 'text-right']); ?>

    <?= $form->field($model, 'url')->textInput() ?>
    <?= $form->field($model, 'alias')->textInput() ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'h1')->textInput() ?>
    <?= $form->field($model, 'keywords')->textInput() ?>
    <?= $form->field($model, 'description')->textarea() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? yii::$app->l->t('Create') : yii::$app->l->t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div> 

    <?php ActiveForm::end(); ?>

</div>
