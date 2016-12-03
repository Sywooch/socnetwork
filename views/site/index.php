<?php

use app\components\extend\Url;
use app\components\extend\Html;
use app\models\Menu;
use app\components\extend\Nav;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = yii::$app->name;
?>
<div class="welcome-head">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-2 logotype">
                <?= Html::a(Html::img('/public/bps/img/welcome-logo.png'), Url::to(['/'])) ?>
            </div>
            <div class="col-md-4 col-sm-6 welcome-nav">
                <?=
                Nav::widget([
                    'options' => ['class' => 'header_menu'],
                    'items' => Menu::getMenuArray(Menu::TYPE_MAIN),
                ]);
                ?>
            </div>
            <div class="col-md-4 col-sm-4 auth-link">
                <?= Html::a(yii::$app->l->t('sign in'), Url::to(['/site/login'])) ?>
                <?=
                Html::a(yii::$app->l->t('sign up'), Url::to(['/site/signup']), [
                    'class' => 'register'
                ])
                ?>
            </div>
        </div>
    </div>
</div>
<div class="welcome-content">
    <div class="container">
        <img src="/public/bps/img/logo.png" alt="">
        <p class="text">
            Добро пожаловать в <span>BPS!</span>
            <small>Уверяю, ты здесь задержишся.</small>
            <a href="/about.html">Подробнее>></a>
        </p>
        <ul class="icon-list">
            <li>
                <img src="/public/bps/img/icons/2.png" alt=""> 
<?= yii::$app->l->t('do create'); ?>
            </li>
            <li>
                <img src="/public/bps/img/icons/1.png" alt=""> 
<?= yii::$app->l->t('do find'); ?>
            </li>
            <li>
                <img src="/public/bps/img/icons/7.png" alt=""> 
<?= yii::$app->l->t('get connected'); ?>
            </li>
            <li>
                <img src="/public/bps/img/icons/6.png" alt=""> Знакомся
            </li>
            <li>
                <img src="/public/bps/img/icons/5.png" alt=""> Общайся
            </li>
            <li>
                <img src="/public/bps/img/icons/4.png" alt=""> Делись
            </li>
            <li>
                <img src="/public/bps/img/icons/3.png" alt=""> Зарабатывай
            </li>
        </ul>
    </div>
</div>
