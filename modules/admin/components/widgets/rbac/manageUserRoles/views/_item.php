<?php

use \app\components\extend\Html;
use \yii\helpers\Json;
use \app\modules\admin\components\rbac\rbac;
use \yii\rbac\Item;
?>

<?php
$tmp = '';
$data = Json::encode($item->data);

$heading = Html::tag('h5', $item->getTitle(), ['class' => 'list-group-item-heading']);
$text = Html::tag('p', $item->getDesc(), ['class' => 'list-group-item-text']);
$text.= Html::tag('br');
$tmp.= Html::tag('a', $heading . $text . $this->render('_role_buttons', ['item' => $item]), [
            'class' => 'list-group-item item role',
            'data' => [
                'id' => $item->name,
                'title' => $item->getTitle(),
                'params' => $data,
            ],
            'href' => '#',
        ]);

echo Html::tag('div', $tmp, [
    'data' => [
        'item-container' => $item->name
    ]
]);
