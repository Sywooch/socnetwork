<?php

use app\components\extend\ActiveForm;
use app\components\extend\Html;
use app\components\widgets\uploader\UploaderWidget;

/* @var $model \app\models\User */
?>

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
        <div class="row">
            <div class="text-right col-md-2">
                <label class="control-label">
                    <?= $model->getAttributeLabel('avatar'); ?>
                </label>
            </div>
            <div class="text-left col-md-4">
                <?= $model->renderAvatar(['size' => app\models\File::SIZE_ORIGINAL]); ?>
            </div>
        </div>
        <?= $form->field($model, 'avatar')->widget(UploaderWidget::className(), ['template' => '_default'])->label(''); ?>
        <div class="clearfix"></div>
        <?= $form->field($model, 'first_name') ?>
        <?= $form->field($model, 'last_name') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'country') ?>
        <?= $form->field($model, 'city') ?>
        <?= $form->field($model, 'skype') ?>
        <?= $form->field($model, 'gender')->radioList($model->getGenderLabels()); ?>
        <?= $form->field($model, 'about')->textarea() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= Html::submitButton(yii::$app->l->t('save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>