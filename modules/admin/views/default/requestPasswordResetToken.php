<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = yii::$app->l->t('Request password reset', ['update' => false]);
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Request password reset'));
?>

<?=
Html::tag('p', yii::$app->l->t('please fill out your email. A link to reset password will be sent there'), [
    'class' => 'text-info'
]);
?>

<div class="row">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-5">
        <?= $form->field($model, 'email')->textInput(['class' => 'form-control']) ?>
        <div class="form-group">
            <?= Html::submitButton(yii::$app->l->t('send'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

