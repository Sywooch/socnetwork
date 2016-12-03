<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Languages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="languages-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'language_id')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'language_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'language_active')->radioList($model->getStatusLabels(false, false)) ?>
            <?= $form->field($model, 'language_is_default')->checkbox(['label' => $model->getAttributeLabel('language_is_default')]); ?>
        </div>
    </div>


    <hr/>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? yii::$app->l->t('create') : yii::$app->l->t('update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div> 

    <?php ActiveForm::end(); ?>

</div>
