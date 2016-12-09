<?php

namespace app\components;

use yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\extend\Controller;
use app\models\Settings;

class FrontendController extends Controller
{

    public $allowActions = ['captcha'];

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
                        'actions' => $this->allowActions,
                        'allow' => true,
                    ],
                        [
                        'controllers' => ['site'],
                        'actions' => [
                            'change-language',
                            'error',
                            'login',
                            'contact',
                            'about',
                            'index',
                            'signup',
                            'download',
                            'ln',
                            'reset-password',
                            'request-password-reset'
                        ],
                        'allow' => true,
                    ],
                        [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                        [
                        'actions' => [$this->action->id],
                        'allow' => yii::$app->user->can(),
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * in controller call this function inside "behaviors" method
     * @param array $actions
     * @return array
     */
    public function addAllowedActions($actions = [])
    {
        return $this->allowActions = array_merge($this->allowActions, $actions);
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
                return $this->refresh();
            } else {
                $this->setMessage('error');
                return $this->refresh();
            }
        }
    }

}
