<?php

use app\models\Menu;
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
    <?php
    yii::$app->view->registerJs("$(function () {
            $('#jsTreeMenuWidgetForm').on('changed.jstree', function (\$e, \$data) {
                var \$id = \$data.instance.get_node(\$data.selected[0]).id;
                $('input[name=\"Menu[parent]\"]').val(\$id == 'root' ? 0 : \$id);
            });
    });", yii\web\View::POS_READY);
    ?>
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
