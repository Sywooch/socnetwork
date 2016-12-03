<?php

namespace app\components\widgets\search;

use yii\web\AssetBundle;

class SearchWidgetAssets extends AssetBundle
{
    public $sourcePath = '@app/components/widgets/search/assets';
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