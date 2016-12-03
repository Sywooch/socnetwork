<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

$this->title = yii::$app->l->t('reset password', ['update' => false]);
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('reset password'));
?>
<?=
Html::tag('p', yii::$app->l->t('please choose your new password'), [
    'class' => 'text-info'
]);
?>
<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control']) ?>
        <div class="form-group">
            <?= Html::submitButton(yii::$app->l->t('save'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
