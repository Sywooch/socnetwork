<?php

use yii\web\AssetConverter;

return [
    'appendTimestamp' => true,
    'converter' => [
        'class' => 'yii\web\AssetConverter',
        'forceConvert' => false,
        'commands' => [
            'less' => ['css', $params['lessc-path'] . ' {from} {to} --no-color'],
        ],
    ],
];
