<?php

$plugins = [
    'js' => [
        'public/plugins/shape/shape.min.js',
        'public/plugins/md5.min.js',
        'public/plugins/bootbox.min.js',
        'public/plugins/bootstrap-notify.min.js',
        'public/plugins/swiper/swiper.min.js',
        'public/plugins/yii/yii.js',
    ],
    'css' => [
        'public/plugins/animate/animate.less',
        'public/plugins/shape/shape.min.css',
        'public/plugins/swiper/swiper.min.css',
    ]
];

$common = [
    'js' => array_merge($plugins['js'], [
        'public/js/common.js',
    ]),
    'css' => array_merge($plugins['css'], [
        'public/css/common.less'
    ])
];

return $common;