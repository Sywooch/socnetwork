<?php
return [
    'class' => 'yii\gii\Module',
    'allowedIPs' => ['*'],
    'generators' => [
        'crud' => [
            'class' => 'app\templates\gii\crud\Generator',
            'templates' => [
                'crud' => '@app/templates/gii/crud',
            ]
        ],
        'model' => [
            'class' => 'app\templates\gii\model\Generator',
            'templates' => [
                'model' => '@app/templates/gii/model',
            ]
        ]
    ],
];
