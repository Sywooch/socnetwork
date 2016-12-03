<?php

namespace app\modules\admin\components\widgets\menu;

use yii\web\AssetBundle;

class MenuTreeWidgetAssets extends AssetBundle
{

    public $sourcePath = '@app/modules/admin/components/widgets/menu/assets';
    public $js = [
        'js.js',
    ];
    public $css = [
        'style.css',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
