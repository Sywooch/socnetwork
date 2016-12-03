<?php

use app\components\extend\Nav;
use app\components\extend\NavBar;
use app\components\extend\Html;
use app\models\Settings;

$controller = yii::$app->controller->id;
$action = yii::$app->controller->action->id;
$settingsActive = ($controller == 'settings' && $action == 'update' && !yii::$app->request->get('m'));


NavBar::begin([
//    'brandLabel' => Html::icon('dashboard',['class'=>'visible-sm visible-md visible-lg']).Html::tag('span',yii::$app->name,['class'=>'visible-xs']),
    'brandLabel' => Html::tag('span', yii::$app->name, ['class' => 'text-info']),
    'brandUrl' => ['/admin'],
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top main-top-nav',
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    'items' => [
        require(__DIR__ . '/_menu/_user.php'),
        require(__DIR__ . '/_menu/_languages.php'),
        require(__DIR__ . '/_menu/_content.php'),
            [
            'label' => yii::$app->l->t('settings'),
            'linkOptions' => ['icon' => 'cogs'],
            'url' => ['/admin/settings/update'],
            'active' => $settingsActive,
            'visible' => yii::$app->user->can('settings-update')
        ],
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => yii::$app->l->menuLanguages,
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
            [
            'label' => yii::$app->l->t('site'),
            'url' => 'http://' . Settings::getValue('tld') . '/',
            'linkOptions' => [
                'target' => '_blank',
                'data-pjax' => 0,
            ]
        ]
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        require(__DIR__ . '/_menu/_profile.php'),
    ],
]);
NavBar::end();
?>