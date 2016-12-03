<?php

namespace app\components\extend;

use Yii;
use yii\web\View;
use app\components\helper\Helper;

class Themes extends \yii\base\Theme
{

    const THEMES_FOLDER = '/themes/';

    public $themeName;

    /**
     * set theme by name (@app/themes/$themeName/...)
     */
    public function setTheme()
    {
        Yii::$app->set('view', [
            'class' => View::className(),
            'theme' => [
                'class' => Themes::className(),
                'themeName' => $this->themeName,
                'pathMap' => [
                    '@app/views' => '@app' . Themes::THEMES_FOLDER . $this->themeName . '/' . yii::$app->id . '/views',
                    '@app/modules/' . yii::$app->id . '/views' => '@app' . Themes::THEMES_FOLDER . $this->themeName . '/' . yii::$app->id . '/views'
                ],
            ],
        ]);

        $asset = '\app\assets\\' . ucfirst(yii::$app->id) . 'Assets'; 
        $asset::register(yii::$app->view);
    }

}
