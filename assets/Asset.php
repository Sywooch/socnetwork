<?php

namespace app\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Asset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\gii\TypeAheadAsset',
    ];
    public $moduleId;
    public $themeName;

    public function init()
    {
        parent::init();

        if (is_a(Yii::$app, 'yii\web\Application')) {
            $this->moduleId = yii::$app->id;
        }
        if (is_a(Yii::$app, 'yii\web\Application')) {
            $this->themeName = '/' . yii::$app->view->theme->themeName;
        }

        $common = require __DIR__ . '/_common.php';
        $this->js = array_merge($common['js'], $this->js);
        $this->css = array_merge($common['css'], $this->css);

        $this->setThemeCss();
        $this->setThemeJs();
    }

    /**
     * set theme css
     */
    public function setThemeCss()
    {
        foreach ([
    'public/css/' . $this->moduleId . '/all-themes.less',
    'public/css/' . $this->moduleId . $this->themeName . '.less',
        ] as $l) {
            if (is_file($l)) {
                $this->css[] = $l;
            }
        }
    }

    /**
     * set theme js
     */
    public function setThemeJs()
    {
        foreach ([
    'public/js/' . $this->moduleId . '/all-themes.js',
    'public/js/' . $this->moduleId . $this->themeName . '.js',
        ] as $j) {
            if (is_file($j)) {
                $this->js[] = $j;
            }
        }
    }

    /**
     * 
     * @param \yii\web\View $view
     * @return type
     */
    public static function register($view)
    {
        return parent::register($view);
    }

}
