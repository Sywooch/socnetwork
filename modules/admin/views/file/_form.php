<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;
use app\components\widgets\uploader\UploaderWidget;

/* @var $this yii\web\View */
/* @var $model app\models\File */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="carousel-form">

    <?php $form = ActiveForm::begin(); ?>



    <?=
    UploaderWidget::widget([
        'name' => 'file',
        'ajax' => true,
        'template' => '_single',
    ]);
    ?>


    <hr/>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? yii::$app->l->t('Create') : yii::$app->l->t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
