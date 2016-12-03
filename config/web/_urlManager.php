<?php

return [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => false,
    'showScriptName' => false,
    'rules' => [
        'admin' => 'admin/',
        'admin/<action:\w+>' => 'admin/default/<action>',
        'admin/<controller:\w+>/<action:\w+>' => 'admin/<controller>/<action>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        'debug/<controller>/<action>' => 'debug/<controller>/<action>',
    ]
];
