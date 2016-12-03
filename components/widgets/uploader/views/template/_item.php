<?php
/* @var $uploader \app\components\widgets\uploader\UploaderWidget */

use app\components\extend\Html;
?>
<li class="jFiler-item" data-origin-file="{{fi-size}}-{{fi-name}}">
    <div class="jFiler-item-container">
        <div class="jFiler-item-inner">
            <div class="jFiler-item-thumb">
                <div class="jFiler-item-status"></div>
                <div class="jFiler-item-info">
                    <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>
                    <span class="jFiler-item-others">{{fi-size2}}</span>
                </div>
                {{fi-image}}
            </div>
            <?= $uploader->ajax ? '<hr/>' : '' ?>
            <div class="jFiler-item-assets text-center">
                <ul class="list-inline pull-left">
                    <li class="<?= $uploader->ajax ? '' : 'hidden' ?>">{{fi-progressBar}}</li>
                </ul>
                <ul class="list-inline pull-right">
                    <li class="<?= $uploader->ajax ? '' : 'hidden' ?>">
                        <a class="icon-jfi-trash fa fa-trash text-danger jFiler-item-trash-action"></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</li>