<?php

use app\components\extend\Nav;
use app\components\extend\Html;

$menuItems = isset($this->params['menu']) ? $this->params['menu'] : [];
?>

<div class="pageHeader">
    <div class="row">
        <div class="col-lg-8">
            <?= (isset($this->params['pageHeader']) ? $this->params['pageHeader'] : '') ?>
        </div>
        <div class="col-lg-4">
            <?=
            Nav::widget(['options' => [
                    'class' => 'nav nav-pills pull-right actions-menu',
                    'style' => 'margin-top:20px'
                ], 'encodeLabels' => false, 'items' => $menuItems]);
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?= yii::$app->controller->flash ? yii::$app->controller->flash : '' ?>
    <?= (isset($this->params['pageHeader']) || count($menuItems) > 0) ? Html::tag('div', '', ['class' => 'separator']) : '' ?>
</div>