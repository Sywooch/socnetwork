<?php

namespace app\controllers;

use Yii;
use app\components\FrontendController;

class UserController extends FrontendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}