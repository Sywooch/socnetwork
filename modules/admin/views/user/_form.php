<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>




<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-3">
        <?=
        $this->render('_user_avatar', [
            'form' => $form,
            'model' => $model,
        ]);
        ?>
    </div>
    <div class="col-md-9">
        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?php if ($model->scenario != 'profile'): ?>
            <?php
            if ($model->isNewRecord) {
                $model->status = User::STATUS_ACTIVE;
            }
            ?>
            <?= $form->field($model, 'status')->radioList($model->getStatusLabels(false, false)) ?>
            <?php if (yii::$app->user->can('rbac-assignment')): ?>
                <?= $form->field($model, 'rbacRole')->checkboxList($model->getAvailableRoles()); ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<hr/>
<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? yii::$app->l->t('Create') : yii::$app->l->t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

