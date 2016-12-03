<?php

use \app\models\Menu;
use \app\components\extend\Html;
use \app\modules\admin\components\widgets\menu\MenuTreeWidget;
use \app\modules\admin\components\widgets\menu\MenuTreeWidgetAssets;
use \app\components\extend\extensions\JsTree;

MenuTreeWidgetAssets::register($this);

$plugins = [
    "themes",
    "json_data",
    'changed',
    'types'
];
if (yii::$app->user->can('menu-update')) {
    $plugins[] = 'dnd';
}
?>
<div class="menu-widget">
    <?=
    JsTree::widget([
        'id' => 'jsTreeMenuWidget',
        'jsOptions' => [
            'plugins' => $plugins,
            'checkbox' => [
                'three_state' => false,
                "keep_selected_style" => false,
            ],
            'types' => [
                "#" => [
                    "max_children" => 1
                ],
            ],
            'core' => [
                "check_callback" => true,
                'multiple' => false,
                'data' => MenuTreeWidget::getTreeArray($type, $model, true),
                'themes' => [
                    'url' => '/public/plugins/proton/style.min.css',
                    'dots' => true,
                    'icons' => true,
                    'name' => 'proton',
                    'responsive' => true
                ]
            ],
        ]
    ]);
    ?>
</div>
