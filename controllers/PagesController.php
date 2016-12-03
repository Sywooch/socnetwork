<?php

namespace app\controllers;

use \Yii;
use \app\components\FrontendController;
use \app\models\Pages;
use \yii\web\NotFoundHttpException;

class PagesController extends FrontendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    public function actionView($id)
    {
        return $this->render('view', [
                    'model' => $this->findModel($id)
        ]);
    }

    /**
     * Finds the Pages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pages::find()->where(['id' => (int) $id, 'status' => Pages::STATUS_ACTIVE])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}