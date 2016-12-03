<?php

use app\models\Menu;
use app\components\extend\ArrayHelper;
use app\components\extend\Html;
use app\modules\admin\components\widgets\menu\MenuTreeWidget;
use app\modules\admin\components\widgets\menu\MenuTreeWidgetAssets;
use app\components\extend\extensions\JsTree;

MenuTreeWidgetAssets::register($this);

$plugins = [
    "themes",
    "json_data",
    "checkbox",
    'ccrm',
    'ui',
    'types'
];
?>
<div class="menu-widget">
    <?=
    JsTree::widget([
        'id' => 'jsTreeMenuWidgetForm',
        'jsOptions' => [
            'plugins' => $plugins,
            'checkbox' => [
                'three_state' => false,
                'check_callback' => true
            ],
            'types' => [
                "#" => [
                    "max_children" => 1
                ],
            ],
            'core' => [
                'multiple' => false,
                'data' => MenuTreeWidget::getTreeArray($type, $model),
                'themes' => [
                    'url' => '/public/css/plugins/proton/style.min.css',
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
