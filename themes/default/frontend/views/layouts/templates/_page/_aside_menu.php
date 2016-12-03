<?php

use app\models\Menu;
use app\components\extend\Nav;
?>


<aside class="sidebar" role="complementary">
    <nav role="navigation">


        <?=
        Nav::widget([
            'options' => ['class' => 'block'],
            'items' => Menu::getMenuArray(Menu::TYPE_ASIDE),
        ]);
        ?>

    </nav>
</aside>