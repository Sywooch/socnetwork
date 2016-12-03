<?php

use app\components\extend\Nav;
use app\components\extend\NavBar;
use app\models\Menu;

NavBar::begin([
    'brandLabel' => yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'main-top-nav navbar-inverse navbar-fixed-top',
    ],
]);
echo \app\components\widgets\search\SearchWidget::widget();
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => yii::$app->l->menuLanguages,
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => Menu::getMenuArray(Menu::TYPE_MAIN),
]);
NavBar::end();
