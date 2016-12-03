<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;
use app\components\extend\extensions\Redactor;

/* @var $this yii\web\View */
/* @var $model app\models\Pages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pages-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= Html::tag('div', $model->getTButtons(), ['class' => 'text-right']); ?>

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'title')->textInput() ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model->seo, 'h1')->textInput() ?>
        </div>
    </div>
    <?= $form->field($model, 'content')->widget(Redactor::className(), Redactor::getDefaultSettings()); ?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model->seo, 'alias')->textInput() ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model->seo, 'title')->textInput() ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model->seo, 'keywords')->textarea() ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model->seo, 'description')->textarea() ?>
        </div>
    </div>
    <?=
    $form->field($model, 'status')->radioList($model->getStatusLabels(), [
        'encode' => false
    ])
    ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? yii::$app->l->t('Create') : yii::$app->l->t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div> 

    <?php ActiveForm::end(); ?>

</div>
