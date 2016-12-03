<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii;

/**
 * @author Postolachi Segrghei <dev@it-init.com>
 */
class BpsAssets extends AssetBundle
{

    public $basePath = '@webroot/public/bps';
    public $baseUrl = '@web/public/bps/';
    public $css = [];
    public $js = [];
    public $depends = [
        'app\assets\Asset',
    ];

    /**
     * @return init
     */
    public function init()
    {
        $this->publishOptions['forceCopy'] = true;
        $this->css[] = 'css/bps.less';
        $this->js[] = 'js/bootstrap-editable.min.js';
        $this->js[] = 'js/clipboard/clipboard.min.js';
        $this->js[] = 'js/js.js';
        $this->IE();
        return parent::init();
    }

    public function IE()
    {
        $view = Yii::$app->getView();
        $manager = $view->getAssetManager();
        $view->registerJsFile($manager->getAssetUrl($this, 'js/ie/html5shiv.min.js'), ['condition' => 'lte IE9']);
        $view->registerJsFile($manager->getAssetUrl($this, 'js/ie/respond.min.js'), ['condition' => 'lte IE9']);
    }

}
