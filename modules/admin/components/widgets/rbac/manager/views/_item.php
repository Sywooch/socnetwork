<?php

use \app\components\extend\Html;
use \yii\helpers\Json;
use \app\modules\admin\components\rbac\rbac;
use \yii\rbac\Item;
?>

<?php
$tmp = '';
$data = Json::encode($item->data);
$btnUpdate = Html::tag('div', Html::ico('pencil'), [
            'title' => yii::$app->l->t('update', ['update' => false]),
            'class' => 'pull-right btn-link',
            'onclick' => "showRbacModalForm('" . $item->name . "','" . $item->type . "'," . $data . ");return false;"
        ]);
$btnDelete = Html::tag('div', Html::ico('trash'), [
            'title' => yii::$app->l->t('delete', ['update' => false]),
            'class' => 'pull-right btn-link',
            'onclick' => "deleteRbacItem($(this),'" . $item->name . "'," . $item->type . ");return false;",
            'data' => [
                'confirm-message' => yii::$app->l->t('аre you sure you want to delete this item?')
            ]
        ]);
$heading = Html::tag('h5', $item->getTitle() . $btnUpdate . ' ' . $btnDelete, ['class' => 'list-group-item-heading']);
$text = Html::tag('p', $item->getDesc(), ['class' => 'list-group-item-text']);
$text.= Html::tag('br');
$permissionButtons = ($item->type == Item::TYPE_PERMISSION ? $this->render('_permission_buttons', [
                    'item' => $item
                ]) : '');
$tmp.= Html::tag('a', $heading . $text . $permissionButtons, [
            'class' => 'list-group-item item ' . ($item->type == Item::TYPE_PERMISSION ? 'permission' : 'role'),
            'data' => [
                'id' => $item->name,
                'title' => $item->getTitle(),
                'params' => $data,
                'children' => ($item->type == Item::TYPE_ROLE ? (new rbac)->getChildren($item->name, false) : '[]')
            ],
            'href' => '#',
            'onclick' => 'toggleRole($(this));return false;'
        ]);


echo (isset($noContainer) && $noContainer === true) ? $tmp : Html::tag('div', $tmp, [
            'data' => [
                'item-container' => $item->name
            ]
        ]);
