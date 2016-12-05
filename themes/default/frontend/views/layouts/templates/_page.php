<?php

use app\components\extend\Url;
use app\components\extend\Html;
?>
<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-4">
                <div class="logotype">
                    <?=
                    Html::a(Html::img('/public/bps/img/logo.png', [
                                'width' => 100,
                                'alt' => 'BPS'
                            ]), Url::to(['/']))
                    ?>
                </div>
            </div>
            <div class="col-md-9 col-sm-8 col-xs-8 relative">
                <?= $this->render('_page/_user_menu'); ?>
            </div>
        </div>
    </div>
</header>
<main class="main" role="main">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-4 sidebar-container">
                <?= $this->render('_page/_aside_menu'); ?>
            </div>
            <div class="col-md-9 col-sm-8 content-container">
                <div class="content">
                    <?= $this->render('../_page_header'); ?>
                    <?= $content; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->render('_page/_footer_menu'); ?>