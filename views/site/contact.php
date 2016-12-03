<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;
use yii\captcha\Captcha;
use app\components\helper\Helper;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = 'Contact';
$this->params['breadcrumbs'][] = Helper::data()->getParam('h1', $this->title);
$this->params['pageHeader'] = Html::tag('h1', Helper::data()->getParam('h1', $this->title));
?>


<?=
Html::tag('p', yii::$app->l->t('If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.'), [
    'class' => 'text-info'
]);
?>

<?php
$form = ActiveForm::begin([
            'id' => 'contact-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'action' => '/site/contact',
            'options' => [
                'enctype' => 'multipart/form-data',
                'data' => [
                    'pjax' => true
                ]
            ]
        ]);
?>
<div class="row">
    <div class="col-lg-6">
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'subject') ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'body')->textArea(['rows' => 9]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <?=
        $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="input-group ">{input}<div class="btn-captcha_ btn btn-primary">{image}</div></div>',
            'imageOptions' => [
//                'style' => 'height:auto;',
                'title' => yii::$app->l->t('update', ['update' => false]),
            ]
        ])->label()
        ?>
    </div>
</div>

<hr/>
<div class="form-group">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-default', 'name' => 'contact-button']) ?>
</div>
<?php ActiveForm::end(); ?>