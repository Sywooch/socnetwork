<?php
$menuActive = ($controller == 'menu');
$pagesActive = ($controller == 'pages');
$fileActive = ($controller == 'file');

$seoActive = ($controller == 'seo');
$canMenu = yii::$app->user->can('menu-index');
$canSeo = yii::$app->user->can('seo-index');
$canPages = yii::$app->user->can('pages-index');
$canFile = yii::$app->user->can('file-index');
$canCarousel = yii::$app->user->can('pages-carousel');

return [
    'label' => yii::$app->l->t('content'),
    'linkOptions' => ['icon' => 'paperclip'],
    'visible' => ($canMenu || $canPages || $canSeo || $canCarousel || $canFile),
    'active' => ($menuActive || $pagesActive || $seoActive || $fileActive),
    'items' => [
        [
            'label' => yii::$app->l->t('menu'),
            'linkOptions' => ['icon' => 'bars'],
            'url' => ['/admin/menu/index'],
            'visible' => $canMenu,
            'active' => $menuActive
        ],
        [
            'label' => yii::$app->l->t('pages'),
            'linkOptions' => ['icon' => 'file-text-o'],
            'url' => ['/admin/pages/index'],
            'visible' => $canPages,
            'active' => $pagesActive
        ],
        [
            'label' => yii::$app->l->t('seo'),
            'linkOptions' => ['icon' => 'link'],
            'url' => ['/admin/seo/index'],
            'visible' => $canSeo,
            'active' => $seoActive
        ],
        [
            'label' => yii::$app->l->t('files'),
            'linkOptions' => ['icon' => 'file'],
            'url' => ['/admin/file/index'],
            'visible' => $canFile,
            'active' => $fileActive
        ],
    ]
];
