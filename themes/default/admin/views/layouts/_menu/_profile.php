<?php
$profileActive = ($controller == 'user' && $action == 'profile');

$userMenuItem = [
    'label' => !yii::$app->user->isGuest ? Yii::$app->user->identity->username : '',
    'active' => ($profileActive),
    'linkOptions' => ['icon' => 'user'],
    'items' => [
        [
            'label' => yii::$app->l->t('profile'),
            'linkOptions' => ['icon' => 'user'],
            'url' => ['/admin/user/profile'],
            'active' => $profileActive
        ],
        [
            'label' => yii::$app->l->t('quit'),
            'linkOptions' => ['icon' => 'sign-out'],
            'url' => ['/admin/default/logout'],
            'active' => false
        ],
    ]
];

$guestMenuItem = [
    'label' => '',
    'linkOptions' => ['icon' => 'sign-in', 'iconOptions' => ['title' => yii::$app->l->t('sign in')]],
    'url' => ['/admin/default/login'],
    'active' => $action === 'login'
];

return yii::$app->user->isGuest ? $guestMenuItem : $userMenuItem;
