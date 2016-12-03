<?php

namespace app\modules\admin\controllers;

use yii;
use app\models\Settings;
use yii\filters\AccessControl;
use app\modules\admin\components\AdminController;
use yii\helpers\Json;
use app\components\extend\Url;
use app\components\helper\Helper;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends AdminController
{

    public $defaultAction = 'update';
    public $m = 'Settings';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {

        $get = yii::$app->request->get('m');
        $action = ($get == $this->m || !$get) ? 'settings-update' : strtolower($get) . '-settings';
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => [$this->action->id],
                        'allow' => yii::$app->user->can($action),
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($m = null)
    {
        if ($m === null)
            $m = $this->m;
        $model = (new Settings)->generateSettingsModel($m);
        $this->restoreDefaults($m);
        if ($response = $this->saveModel($model)) {
            return $response;
        }

        return $this->render($this->action->id, [
                    'model' => $model,
                    'modelName' => $m,
                    'settings' => Settings::normalize($model->getAllSettings(), $model)
        ]);
    }

    /**
     * restore default settings
     * @param string $m model name
     */
    public function restoreDefaults($m)
    {
        if (yii::$app->request->isAjax && yii::$app->request->isPost && !yii::$app->request->isPjax) {
            $post = yii::$app->request->post();
            if (isset($post['type']) && $post['type'] == 'restoreDefaults') {
                $ar['result'] = 'error';
                $ar['message'] = Helper::str()->getDefaultMessage('error');
                $d = Settings::deleteAll('model=:m', ['m' => strtolower((string) $m)]);
                if ($d) {
                    $ar['result'] = 'success';
                    $ar['message'] = Helper::str()->getDefaultMessage('success');
                    $this->setMessage('success');
                }
                die(Json::encode($ar));
            }
        }
    }

    /**
     * @return mixed
     */
    public function saveModel($m)
    {
        $post = yii::$app->request->post("Settings");
        if (is_object($m)) {
            $m = $m->shortClassName;
        }
        if ($post && array_key_exists(((string) $m), $post)) {
            $saved = true;
            foreach ($post[$m] as $k => $v) {
                $model = new Settings();
                if ($settings = Settings::find()->where('(`key`=:k AND `model`=:m)', ['m' => (string) $m, 'k' => (string) $k])->one()) {
                    $model = $settings;
                }
                $model->key = (string) $k;
                $model->model = (string) $m;
                $model->value = @serialize($v);
                if (!$model->save()) {
                    $saved = false;
                    break;
                }
            }
            if ($saved) {
                $this->setMessage('success');
            } else {
                $this->setMessage('error');
            }
            return $this->redirect(Url::to(['settings/update', 'm' => $m]));
        }
    }

}
