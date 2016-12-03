<?php

namespace app\components\widgets\uploader;

use yii\web\AssetBundle;

class UploaderWidgetAssets extends AssetBundle
{
    public $sourcePath = '@app/components/widgets/uploader/assets';
    public $js = [
    ];
    public $css = [
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        $this->js[] = (YII_ENV_DEV ? 'jquery.filer.js' : 'jquery.filer.min.js');
        $this->js[] = (YII_ENV_DEV ? 'manage.js' : 'manage.min.js');
        $this->css[] = (YII_ENV_DEV ? 'jquery.filer.css' : 'jquery.filer.min.css');
        return parent::init();
    }

}