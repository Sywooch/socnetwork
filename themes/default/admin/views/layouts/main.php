<?php

use app\components\extend\Html;
use app\components\helper\Helper;
use app\components\widgets\carousel\CarouselWidget;
use app\assets\Asset;
use app\components\extend\Pjax;
use app\components\extend\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode(Helper::data()->getParam('pageTitle', $this->title)) ?></title>
        <?php $this->head() ?>
    </head>
    <body data-notification-top-margin="71">
        <?php
        $this->beginBody();
        if (yii::$app->controller->isPjaxAction) {
            Pjax::begin();
            echo $this->render('_loading');
        }
        ?>
        <div class="wrap">
            <?= $this->render('_menu') ?>
            <div class="container">
                <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]); ?>
            </div>
            <?= yii::$app->request->clearUri == yii::$app->homeUrl ? CarouselWidget::widget() : ''; ?>
            <div class="container">
                <?= $this->render('_page_header') ?>
                <?= $content ?>
            </div>
        </div>
        <?php
        if (yii::$app->controller->isPjaxAction) {
            Pjax::end();
        }
        ?>

        <?php $this->endBody() ?>
        <div class="text-danger test-pjax-status" style="z-index: 1055;position: fixed;top: 0;right: 10px;top: 5px;font-weight: bolder!important;">
        </div>
    </body>
</html>
<?php $this->endPage(); ?>
