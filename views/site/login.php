<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = yii::$app->l->t('authorization', ['update' => false]);
$this->params['pageHeader'] = yii::$app->l->t('authorization');
?>

<?php
$form = ActiveForm::begin([
            'id' => 'login-form',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false
        ]);
?>


<?=
$form->field($model, 'username')->textInput([
    'placeholder' => yii::$app->l->t('email', ['update' => false]),
    'class' => 'form-control'
])->label('');
?>
<?=
$form->field($model, 'password')->passwordInput([
    'placeholder' => yii::$app->l->t('password', ['update' => false]),
    'class' => 'form-control'
])->label('');
?>
<hr/>

<div class="clearfix"></div>
<?=
$form->field($model, 'rememberMe')->checkbox([
    'label' => $model->getAttributeLabel('rememberMe')
]);
?>


<div class="form-group">
    <?= Html::submitButton(yii::$app->l->t('sign in'), ['class' => 'btn btn-default', 'name' => 'login-button']) ?>
</div>


<?php ActiveForm::end(); ?>
