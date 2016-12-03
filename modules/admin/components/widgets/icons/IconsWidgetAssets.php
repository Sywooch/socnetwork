<?php

namespace app\modules\admin\components\widgets\icons;

use yii\web\AssetBundle;

class IconsWidgetAssets extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/components/widgets/icons/assets';
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