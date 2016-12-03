<?php

namespace app\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FrontendAssets extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'app\assets\Asset',
        'app\assets\BpsAssets',
    ];

    public function init()
    {
        parent::init();
        $this->publishOptions['forceCopy'] = true;
        $this->js[] = 'public/js/frontend/default.js';
    }

}
