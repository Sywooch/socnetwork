<?php

namespace app\modules\admin\controllers;

use yii;
use app\models\UserFriends;
use app\models\search\UserFriendsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\extend\ActiveForm;
use yii\helpers\Json;
use app\components\extend\Html;

/**
 * UserFriendsController implements the CRUD actions for UserFriends model.
 */
class UserFriendsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all UserFriends models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserFriendsSearch();
        $dataProvider = $searchModel->search(yii::$app->request->queryParams);
        $model = new UserFriends;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single UserFriends model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserFriends model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserFriends();
        $this->saveModel($model);
            return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing UserFriends model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->saveModel($model);
            return $this->render('update', [
                'model' => $model,
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
        if (($model = UserFriends::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
