<?php

use app\models\Menu;
use app\components\extend\Nav;
use app\components\extend\Html;
use app\components\extend\Url;
?>

<footer class="footer" role="contentinfo">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?=
                Nav::widget([
                    'options' => ['class' => ''],
                    'items' => Menu::getMenuArray(Menu::TYPE_MAIN),
                ]);
                ?>
            </div>
            <div class="col-md-4">
                <ul>
                    <li class="logotype">
                        <?=
                        Html::a(Html::img('/public/bps/img/logo-footer.png', [
                                    'width' => 100
                                ]), Url::to(['/']))
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>