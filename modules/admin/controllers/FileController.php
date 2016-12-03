<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\File;
use app\models\search\FileSearch;
use app\modules\admin\components\AdminController as Controller;
use yii\web\NotFoundHttpException;
use app\components\extend\ActiveForm;
use yii\helpers\Json;
use app\components\extend\Html;
use app\components\widgets\uploader\UploaderWidget;
use app\components\helper\Helper;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all File models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new File;
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
        ]);
    }

    /**
     * Displays a single File model.
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
     * Creates a new File model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new File();
        $this->saveModel($model);
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * @param object $model
     * @return mixed
     */
    public function saveModel($model)
    {
        UploaderWidget::manage(['name' => 'file']);
    }

    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return File the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = File::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
