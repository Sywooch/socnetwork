<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminAssets extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'app\assets\Asset',
    ];

    public function init()
    {
        $this->publishOptions['forceCopy'] = true;
//        $this->css[] = 'public/css/admin/default.css';
//        $this->js[] = 'public/js/admin/default.js';
        return parent::init();
    }

}
