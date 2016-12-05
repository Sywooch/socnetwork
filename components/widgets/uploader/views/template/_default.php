<?php

use app\components\extend\Html;
use app\models\File;

/* @var $uploader app\components\widgets\uploader\UploaderWidget */
/* @var $file File */
$file = $uploader->model ? $uploader->model->getFile($uploader->attribute) : new File();
?>
<div class="input-group bt-uploader<?= $uploader->template ?>" > 
    <input readonly="true" value="" class="form-control" placeholder="<?= yii::$app->l->t('select files'); ?>"> 
    <span class="input-group-btn"> 
        <a style="height: 34px;margin-top: -1px;" class="btn btn-default glyphicon glyphicon-file" type="button"></a> 
    </span> 
</div>


<?php
$selector = '$(".bt-uploader' . $uploader->template . ' input")';
$uploader->onEmpty = 'new function(){'.$selector . '.val("");};';
$uploader->onRemove = 'new function(){'.$selector . '.val("");};';
$uploader->onSelect = 'new function(){'.$selector . '.val($i.name);};';
?>