<?php

namespace app\controllers;

use Yii;
use app\components\FrontendController;

class SearchController extends FrontendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        parent::addAllowedActions(['index']);
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}