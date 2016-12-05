<?php

namespace app\controllers;

use yii;
use app\components\FrontendController;

class UserController extends FrontendController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
