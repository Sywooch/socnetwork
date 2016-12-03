<?php

use app\components\extend\Html;
use yii\grid\CheckboxColumn;
use app\components\extend\Url;
?>

<?php

$url = Url::to(['index']);
echo Html::tag('div', Html::ico('check-circle-o') . yii::$app->l->t('allow this action for {role}', [
            'role' => Html::tag('span', '', ['class' => 'role-title'])
        ]), [
    'class' => 'btn btn-sm btn-default pull-right add-remove-child-button add-child-button',
    'onclick' => 'addRemoveChildToItem($(this));return false;',
    'data' => [
        'loading-text' => yii::$app->l->t('loading...'),
        'id' => $item->name,
        'url' => $url,
        'type' => 'add',
    ]
]);
?>
<?php

echo Html::tag('div', Html::ico('ban') . yii::$app->l->t('disallow this action for {role}', [
            'role' => Html::tag('span', '', ['class' => 'role-title'])
        ]), [
    'class' => 'btn btn-sm btn-danger pull-right add-remove-child-button remove-child-button  hidden',
    'onclick' => 'addRemoveChildToItem($(this));return false;',
    'data' => [
        'loading-text' => yii::$app->l->t('loading...'),
        'id' => $item->name,
        'url' => $url,
        'type' => 'remove',
    ]
]);
?>
<?= Html::tag('div', '', ['class' => 'clearfix']); ?>