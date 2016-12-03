<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\NavBar;
use app\models\Menu;
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right footer-menu'],
                'items' => Menu::getMenuArray(Menu::TYPE_FOOTER),
            ]);
            ?>
        </div>
    </div>
</div>