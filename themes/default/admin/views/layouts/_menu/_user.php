<?php
$userActive = (($controller == 'user' && $action != 'profile') || ($controller == 'settings' && yii::$app->request->get('m') == 'User'));
$rbacActive = $controller == 'rbac';
$canRbac = yii::$app->user->can('rbac-index');
$canUser = yii::$app->user->can('user-index');

return [
    'label' => yii::$app->l->t('users'),
    'linkOptions' => ['icon' => 'users'],
    'active' => ($userActive || $rbacActive),
    'visible' => ($canUser || $canRbac),
    'items' => [
        [
            'label' => yii::$app->l->t('users list'),
            'linkOptions' => ['icon' => 'users'],
            'url' => ['/admin/user/index'],
            'visible' => $canUser,
            'active' => $userActive
        ],
        [
            'label' => yii::$app->l->t('user roles'),
            'linkOptions' => ['icon' => 'lock'],
            'url' => ['/admin/rbac/index'],
            'visible' => $canRbac,
            'active' => $rbacActive
        ]
    ]
];
