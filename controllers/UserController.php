<?php

namespace app\controllers;

use yii;
use app\components\FrontendController;
use app\models\User;

class UserController extends FrontendController
{

    public function init()
    {
        parent::init();
        $this->addAllowedActions([
            'view'
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index', [
                    'user' => yii::$app->user->identity
        ]);
    }

    public function actionView($id)
    {
        return $this->render('index', [
                    'user' => $this->findModel($id)
        ]);
    }

    public function actionSettings()
    {
        $model = yii::$app->user->identity;
        $this->saveModel($model);
        return $this->render('settings', [
                    'model' => $model
        ]);
    }

    /**
     * Finds the UserFriends model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserFriends the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $q = User::find();
        $q->andWhere('id=:uid AND status=:stat', [
            'uid' => (int) $id,
            'stat' => User::STATUS_ACTIVE,
        ]);
        if (($model = $q->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
