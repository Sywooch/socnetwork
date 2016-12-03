<?php

use app\components\extend\Html;
use app\models\File;

/* @var $uploader app\components\widgets\uploader\UploaderWidget */
/* @var $file File */
$file = $uploader->model ? $uploader->model->getFile($uploader->attribute) : new File();
?>
<div class="jFiler-info-container input-group <?= $uploader->template ?>" style="margin-bottom: 1px">
    <?php
    $style = '';
    $class = 'fiInputInfoBrowseButton ';
    if ($uploader->model && $bgUrl = $file->getUrl(File::SIZE_MD)) {
        $style = "background-image: url('$bgUrl')!important";
    } else {
        $class.= 'fa fa-paperclip';
    }
    ?>

    <div class="<?= $class ?>" style="<?= $style; ?>">
        <div class="uploader-browse-text">
            <?php
            echo Html::ico('paperclip') . ' ' . yii::$app->l->t('choose files');
            echo Html::textInput(null, null, [
                'placeholder' => ($file->name ? $file->title : ''),
                'class' => 'form-control fiInputInfoIndicator',
                'readonly' => true,
                'data' => [
                    'counter' => 0,
                    'text' => yii::$app->l->t('selected files', ['update' => false]),
                ]
            ]);
            ?>
        </div>
    </div>
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