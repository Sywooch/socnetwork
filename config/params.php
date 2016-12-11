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
        /* example for $localParams[mailer]
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'myaccountname@gmail.com',
            'password' => 'mypassword',
            'port' => '465',
            'encryption' => 'ssl',
            'plugins' => [
                    [
                    'class' => 'Swift_Plugins_LoggerPlugin',
                    'constructArgs' => [new Swift_Plugins_Loggers_ArrayLogger()],
                ],
            ],
        ],
        */
    ]
];

return array_merge($params, (is_array($localParams) ? $localParams : []));
