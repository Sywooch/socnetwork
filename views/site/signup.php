<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;
use app\components\widgets\uploader\UploaderWidget;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\forms\SignupForm */

$this->title = yii::$app->l->t('Sign up');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHeader'] = Html::encode($this->title);
?>
<p><?= yii::$app->l->t('Please fill out the following fields to signup'); ?>:</p>
<?php
$form = ActiveForm::begin([
            'id' => 'form-signup',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => '<div class="text-right col-md-2">{label}</div><div class="col-md-10">{input}<br/>{hint}</div>',
            ]
        ]);
?>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <?= $form->field($model, 'first_name') ?>
        <?= $form->field($model, 'last_name') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'country') ?>
        <?= $form->field($model, 'city') ?>
        <?= $form->field($model, 'skype') ?>
        <?= $form->field($model, 'gender') ?>
        <?= $form->field($model, 'about')->textarea() ?>
        <?= $form->field($model->user, 'avatar')->widget(UploaderWidget::className(), ['template' => '_default']); ?>
        <?= $form->field($model, 'payment')->radioList($model->getPaymentTypeLabels()); ?>
        <?=
        $form->field($model, 'agree', [
            'template' => '<div class="text-right col-md-2">{label}</div><div class="col-md-10">{input}{error}</div>',
        ])->checkbox([],false)->label($model->getAttributeLabel('agree'));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= Html::submitButton(yii::$app->l->t('Sign up'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>