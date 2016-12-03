<?php

namespace app\modules\admin;

use Yii;

class Admin extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $defaultController = 'default';
    public $layout = 'main';
    public $id = 'admin';
    public $name = 'INIT-CP';

    public function init()
    {
        parent::init();
        Yii::$app->errorHandler->errorAction = $this->id . '/' . $this->defaultController . '/error';
        yii::$app->id = $this->id;
        yii::$app->name = strtoupper($this->name);
        Yii::$app->homeUrl = ['/' . $this->id];
        $this->setUserConf();
    }

    /**
     * user configuration
     */
    public function setUserConf()
    {
        /**
         * user
         */
        Yii::$app->set('user', [
            'identityClass' => 'app\models\User',
            'class' => 'app\components\Users',
            'enableAutoLogin' => true,
            'loginUrl' => [$this->id . '/' . $this->defaultController . '/login'],
            'identityCookie' => ['name' => $this->id, 'httpOnly' => true],
            'idParam' => $this->id,
        ]);
    }

}