<?php

use app\components\extend\Html;
use app\components\helper\Helper;
use app\components\widgets\carousel\CarouselWidget;
use app\assets\Asset;
use app\components\extend\Pjax;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php
$isHomePage = (yii::$app->controller->id == 'site' && yii::$app->controller->action->id == 'index' && yii::$app->user->isGuest);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode(Helper::data()->getParam('pageTitle', $this->title)) ?></title>
        <?php $this->head() ?>
    </head>
    <body data-notification-top-margin="0" >
        <div class="wrap">
            <?php
            $this->beginBody();
            if (yii::$app->controller->isPjaxAction) {
                echo $this->render('_loading');
                Pjax::begin();
            }
            $page = $this->render('templates/' . ($isHomePage ? '_home' : '_page'), ['content' => $content]);
            echo Html::tag('div', $page, ['class' => ($isHomePage ? 'welcome' : '')]);
            if (yii::$app->controller->isPjaxAction) {
                Pjax::end();
            }
            ?>
            <?php
            if (YII_ENV_DEV) {
                echo Html::tag('div', '', [
                    'style' => 'z-index: 1055;position:fixed;top: 0;right: 10px;font-size: 0.6em;top: 0;font-weight: bolder!important;color:#d66e6e!important;',
                    'class' => 'test-pjax-status'
                ]);
            }
            ?>
            <?php $this->endBody() ?>
        </div>
    </body>
</html>
<?php $this->endPage(); ?>
