<?php

namespace app\modules\admin\components;

use yii;
use app\assets\Asset;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\extend\Controller;
use app\components\extend\Html;
use yii\helpers\Json;
use app\components\helper\Helper;
use app\models\Settings;

class AdminController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => ['error', 'resetPassword', 'requestPasswordReset', 'login', 'captcha', 'ln', 'request-password-reset', 'reset-password'],
                        'allow' => true,
                    ],
                        [
                        'actions' => ['logout', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                        [
                        'actions' => [$this->action->id],
                        'allow' => yii::$app->user->can(),
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * @param object $model
     * @return mixed
     */
    public function saveModel($model)
    {
        if ($model->load(yii::$app->request->post())) {
            $this->ajaxValidation($model);
            if ($model->save()) {
                $this->setMessage('success');
                $get = yii::$app->request->get();
                return yii::$app->user->can($this->id . '-view') ? $this->redirect(['view', 'id' => $model->primaryKey] + $get) : $this->refresh();
            } else {
                $this->setMessage('error');
                return $this->refresh();
            }
        }
    }

    /**
     * Deletes an existing Seo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id = false)
    {
        $this->deleteSelected();
        if ($this->findModel($id)->delete()) {
            $this->setMessage('success');
            if (strpos(yii::$app->request->referrer, 'index?') !== false)
                return $this->redirect(yii::$app->request->referrer);
        } else {
            $this->setMessage('error');
            return $this->refresh();
        }

        return $this->redirect(['index']);
    }

    /**
     * delete selected items by ids
     */
    public function deleteSelected()
    {
        $ids = yii::$app->request->post('ids');
        if (yii::$app->request->isPost && is_array($ids)) {
            $model = $this->findModel(@$ids[0]);
            $ar = ['deleted' => 0, 'not-deleted' => 0, 'type' => 'error', 'message' => Helper::str()->getDefaultMessage('error')];
            $post = yii::$app->request->post();
            if (array_key_exists('ids', $post) && is_array($post['ids'])) {
                $models = $model::find()->where(['in', $model::primaryKey(), $post['ids']])->all();
                if ($models) {
                    foreach ($models as $m)
                        $m->delete() ? $ar['deleted'] += 1 : $ar['not-deleted'] += 1;
                    $ar['message'] = Html::tag('p', ($ar['deleted'] > 0 ? Helper::str()->getDefaultMessage('success') : Helper::str()->getDefaultMessage('error')));
                    if (count($post['ids']) > 1 && $ar['deleted'] < count($post['ids'])) {
                        $ar['message'] .= Html::tag('p', yii::$app->l->t('deleted: {deleted}', ['deleted' => $ar['deleted']]), ['class' => 'text-info']);
                        $ar['message'] .= Html::tag('p', yii::$app->l->t('faild to delete: {not-deleted}', ['not-deleted' => $ar['not-deleted']]), ['class' => 'text-danger']);
                    }
                    $ar['type'] = $ar['deleted'] === count($post['ids']) ? 'success' : 'warning';
                }
            }
            die(Json::encode($ar));
        }
    }

}
