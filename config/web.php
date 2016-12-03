<?php

$params = require(__DIR__ . '/params.php');
$assetManager = require (__DIR__ . '/web/_assetManager.php');
$i18n = require (__DIR__ . '/web/_i18n.php');
$urlManager = require (__DIR__ . '/web/_urlManager.php');
$log = require (__DIR__ . '/web/_log.php');
$db = require(__DIR__ . '/db.php');
$mailer = $params['mailer'];


$config = [
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'BPS',
    'id' => 'frontend',
    'language' => 'en',
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Admin',
        ],
        'gii' => require (__DIR__ . '/web/_gii.php')
    ],
    'timeZone' => 'Europe/Chisinau',
    'components' => [
        'formatter' => [
            'dateFormat' => 'yyyy-MM-d',
            'timeFormat' => 'H:mm:ss',
            'datetimeFormat' => 'Y-M-d H:mm',
        ],
        'l' => ['class' => 'app\models\Languages',],
        'helper' => ['class' => 'app\components\helper\CommonHelper',],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'class' => 'app\components\Users',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => ['errorAction' => 'site/error',],
        'mailer' => $mailer,
        'request' => ['class' => 'app\components\Request',
            'enableCsrfValidation' => true,
            'cookieValidationKey' => '9goCabMqPN4_GM-TBw1eaHrhJoJxUqL0',
        ],
        'assetManager' => $assetManager,
        'i18n' => $i18n,
        'urlManager' => $urlManager,
//        'log' => $log,
        'cache' => ['class' => 'yii\caching\FileCache',],
        'db' => $db,
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
// configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
//        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*'],
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = require (__DIR__ . '/web/_gii.php');
}
//echo YII_ENV_DEV?'ENV':'NO-ENV';
//echo '<pre>' . print_r($config['modules']['debug'], TRUE) . '</pre>';
//die();

if (!YII_ENV_PROD) {
//    $config['components']['assetManager'] = [
//        'bundles' => require(__DIR__ . '/../assets/min.php'),
//    ];
}
return $config;
