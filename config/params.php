<?php

$localFile = __DIR__ . '/params-local.php';
$localParams = is_file($localFile) ? require $localFile : [];

$params = [
    'adminEmail' => 'gaftonsifon@yandex.com',
    'supportEmail' => 'gaftonsifon@yandex.com',
    'user.passwordResetTokenExpire' => 3600,
    'lessc-path' => '/usr/local/bin/lessc',
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'viewPath' => '@app/mail',
        'useFileTransport' => false,
    ]
];

return array_merge($params, (is_array($localParams) ? $localParams : []));
