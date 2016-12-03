<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Menu;
use app\models\search\MenuSearch;
use app\modules\admin\components\AdminController;
use yii\web\NotFoundHttpException;
use app\components\extend\ActiveForm;
use yii\helpers\Json;
use app\components\extend\Html;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends AdminController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex($type = null)
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new Menu;
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'type' => ($type ? $type : Menu::TYPE_MAIN),
                    'model' => $model,
        ]);
    }

    /**
     * Displays a single Menu model.
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
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = null)
    {

        $model = new Menu();
        $model->type = $type;
        $this->getMenuItemsJson($model);
        if ($response = $this->saveModel($model)) {
            return $response;
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->updatePositionsAndParents();
        $model = $this->findModel($id);
        $this->getMenuItemsJson($model);
        if ($response = $this->saveModel($model)) {
            return $response;
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function updatePositionsAndParents()
    {
        if (yii::$app->request->isAjax) {
            $post = yii::$app->request->post();
            $rightData = (isset($post['items'], $post['type']) && is_array($post['items']));
            if ($rightData && $post['type'] === 'updatePositionsAndParents') {
                $arr = ['type' => 'error'];
                foreach ($post['items'] as $k => $v) {
                    if (isset($v['order'], $v['parent']) && $m = $this->findModel((int) $k, false)) {
                        $m->parent = $v['parent'];
                        $m->order = $v['order'];
                        if ($m->save()) {
                            $arr['message'] = yii::$app->l->t('Operation succeeded');
                            $arr['type'] = 'success';
                        } else {
                            $arr['message'] = yii::$app->l->t('Operation failed');
                        }
                    }
                }
                die(Json::encode($arr));
            }
        }
    }

    /**
     * conver menu object into json for jsTree widget
     * return Json  
     */
    public function getMenuItemsJson($model)
    {
        $get = yii::$app->request->get();
        if (yii::$app->request->isAjax && isset($get['operation']) && $get['operation'] === 'treeArray') {
            $type = (isset($get['type']) ? (int) $get['type'] : Menu::TYPE_MAIN);
            $ar = \app\modules\admin\components\widgets\menu\MenuTreeWidget::getTreeArray($type, $model);
            die(Json::encode($ar));
        }
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param boolean $throw (throw exception)
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $throw = true)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            if ($throw) {
                throw new NotFoundHttpException('The requested page does not exist.');
            } else {
                return null;
            }
        }
    }

}
