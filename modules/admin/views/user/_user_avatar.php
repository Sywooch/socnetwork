<?php

use app\components\widgets\uploader\UploaderWidget;
use app\models\File;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<?= $form->field($model, 'avatar')->widget(UploaderWidget::className())->label(); ?>
