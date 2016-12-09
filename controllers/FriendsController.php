<?php

namespace app\controllers;

use yii;
use app\models\UserFriends;
use app\components\FrontendController;
use yii\web\NotFoundHttpException;
use app\components\extend\ActiveForm;
use yii\helpers\Json;
use app\components\extend\Html;
use app\models\search\UserFriendsSearch;

/**
 * UserFriendsController implements the CRUD actions for UserFriends model.
 */
class FriendsController extends FrontendController
{

    /**
     * Lists all UserFriends models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new UserFriends;
        $searchModel = new UserFriendsSearch();
        $searchModel->user_id = yii::$app->user->id;
        $friends = $searchModel->searchFriends();
        $requests = $searchModel->searchRequests();
        return $this->render('index', [
                    'requests' => $requests,
                    'friends' => $friends,
                    'model' => $model,
        ]);
    }

    /**
     * invite friend
     */
    public function actionInvite($id)
    {
        $model = $this->findSentModel($id);
        if (!$model) {
            $model = new UserFriends();
            $model->user_id = (int) $id;
            $model->sender_id = yii::$app->user->id;
            $model->status = UserFriends::STATUS_REQUEST;
            $this->setMessage(($model->save() ? 'success' : 'error'));
        }
    }

    /**
     * cancel invitation
     */
    public function actionCancel($id)
    {
        $model = $this->findSentModel($id);
        if ($model) {
            $this->setMessage(($model->delete() ? 'success' : 'error'));
        }
    }

    /**
     * accept friend request
     */
    public function actionAccept($id)
    {
        $model = $this->findModel($id);
        $model->status = UserFriends::STATUS_IS_FRIEND;
        $this->setMessage(($model->save() ? 'success' : 'error'));
    }

    /**
     * reject friend request
     */
    public function actionReject($id)
    {
        $model = $this->findModel($id);
        $model->status = UserFriends::STATUS_REJECTED;
        $this->setMessage(($model->save() ? 'success' : 'error'));
    }

    /**
     * reject friend request
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->setMessage(($model->delete() ? 'success' : 'error'));
    }

    /**
     * Finds the UserFriends model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $senderId
     * @return UserFriends the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($senderId)
    {
        $q = UserFriends::find();
        $q->andWhere('user_id=:uid AND sender_id=:sid', [
            'uid' => yii::$app->user->id,
            'sid' => (int) $senderId,
        ]);
        if (($model = $q->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the UserFriends model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $receiverId
     * @return UserFriends the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findSentModel($receiverId)
    {
        $q = UserFriends::find();
        $q->andWhere('user_id=:uid AND sender_id=:sid', [
            'uid' => (int) $receiverId,
            'sid' => yii::$app->user->id,
        ]);
        return $q->one();
    }

}
