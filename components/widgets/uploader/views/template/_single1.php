<?php

use app\components\extend\Html;
use app\models\File;

/* @var $uploader app\components\widgets\uploader\UploaderWidget */
/* @var $file File */
$file = $uploader->model ? $uploader->model->getFile($uploader->attribute) : new File();
?>
<div class="btn btn-default btn-sm jFiler-info-container input-group <?= $uploader->template ?>" style="margin-bottom: 1px">
    <?php
    $file = $model = $uploader->model->getFile($uploader->attribute);
    if ($file instanceof File) {
        echo $file->renderImage([
            'size' => File::SIZE_MD
        ]);
        echo Html::ico('pencil', [
            'style' => 'position: absolute;
                        left: 42%;
                        top: 38%;
                        background: rgba(169, 169, 169, 0.25);
                        border-radius: 200px;
                        padding: 10px 11px;
                        color: rgba(0, 0, 0, 0.9)!important;
                        font-size: 1.5em;
                        box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.58);'
        ]);
    } else {
        ?>
        <i class="fa fa-file"></i>
        <?= yii::$app->l->t('Upload'); ?>
        <?php
    }
    ?>
</div>

<?php
$uploader->onEmpty = '(new function(){
                            //$selector, $p, $o, $s
                           $(".fiInputInfoBrowseButton",$selector).css("background-image","none");
                           $(".fiInputInfoBrowseButton",$selector).addClass("fa fa-paperclip");
        })';

$uploader->afterShow = '(new function(){
    var $container = $container;
     setTimeout(function () {
        var $thumb = $(".jFiler-item-thumb-image *:first-child", $container);
        var $fiBtn = $(".fiInputInfoBrowseButton", $container);
        $fiBtn.attr("class", "fiInputInfoBrowseButton ");
        if ($thumb.attr("src") != undefined) {
            $fiBtn.css({
                "background-image": "url(" + $thumb.attr("src") + ")",
            });
        } else {
            $fiBtn.addClass($thumb.attr("class"));
            $fiBtn.css({
                "background-image": "none"
            });
        }
        fiInputInfoIndicator($container);
    }, 200);
})';
?>