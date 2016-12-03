<?php

use app\components\extend\Html;
use \app\components\extend\Url;
?>

<?php
echo Html::tag('div', Html::ico('check-circle-o') . yii::$app->l->t('add this role to {username}', [
            'username' => Html::tag('span', '', ['class' => 'user-name'])
        ]), [
    'class' => 'btn btn-sm btn-default pull-right add-remove-role-button add-role-button',
    'onclick' => 'addRemoveUserAssignment($(this));return false;',
    'data' => [
        'action' => Url::to(['rbac/assignment']),
        'loading-text' => yii::$app->l->t('loading...'),
        'title' => $item->getTitle(),
        'id' => $item->name,
        'type' => 'add',
    ]
]);
?>
<?php
echo Html::tag('div', Html::ico('ban') . yii::$app->l->t('remove this role from {username}', [
            'username' => Html::tag('span', '', ['class' => 'user-name'])
        ]), [
    'class' => 'btn btn-sm btn-danger pull-right add-remove-role-button remove-role-button hidden',
    'onclick' => 'addRemoveUserAssignment($(this));return false;',
    'data' => [
        'action' => Url::to(['rbac/assignment']),
        'loading-text' => yii::$app->l->t('loading...'),
        'title' => $item->getTitle(),
        'id' => $item->name,
        'type' => 'remove',
    ]
]);
?>
<?= Html::tag('div', '', ['class' => 'clearfix']); ?>