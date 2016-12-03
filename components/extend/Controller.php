<?php

namespace app\components\extend;

use yii;
use yii\filters\VerbFilter;
use yii\web\Controller as BaseController;
use yii\helpers\Json;
use app\components\helper\Helper;
use app\models\Settings;
use app\components\extend\Themes;
use yii\web\View;
use app\components\extend\Url;

class Controller extends BaseController
{

    public $flash;
    public $themeName = 'default';
    public $messages;
    public $isPjaxAction;

    /**
     * @inheritdoc
     */
    public function init()
    {
        Helper::data()->setParam('adminEmail', (new Settings)->getSetting('adminEmail'));
        $this->isPjaxAction = Settings::getValue('usePjax' . $this->module->id);
        return parent::init();
    }

    /**
     * set flash message
     * @param type $type
     * @param type $message
     */
    public function setMessage($type = 'info', $message = null)
    {
        if (!$message) {
            $message = Helper::str()->getDefaultMessage($type);
        }
        yii::$app->session->setFlash($type, $message);
    }

    /**
     * get flash
     */
    public function getFlashMessages()
    {
        $messages = Yii::$app->session->getAllFlashes();
        foreach ($messages as $key => $message) {
            $m = 'yii.mes("' . str_replace('"', '\"', $message) . '","' . $key . '")';
            yii::$app->view->registerJs($m, View::POS_END);
        }
        yii::$app->params['flash'] = $this->flash;
    }

    /**
     * change language
     */
    public function actionChangeLanguage($id)
    {
        yii::$app->l->changeLanguage($id);
        return $this->redirect(yii::$app->request->referrer);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $ba = parent::beforeAction($action);
        if ($seo = yii::$app->request->seo) {
            $seo->setMetaData($this);
        }
        (new Themes(['themeName' => $this->themeName]))->setTheme();
        $this->getFlashMessages();
        if ($this->isPjaxAction && $pjaxUrl = Helper::data()->getSession('X-PJAX-URL', null)) {
            yii::$app->response->headers->set('X-PJAX-URL', $pjaxUrl);
            Helper::data()->removeSession('X-PJAX-URL');
        }
        return $ba;
    }

    /**
     * @inheritdoc
     */
    public function render($view, $params = array())
    {
        if (yii::$app->request->isAjax) {
            $aj = $this->renderAjax($view, $params);
            return $this->renderContent($aj);
        } else {
            return parent::render($view, $params);
        }
    }

    /**
     * @inheritdoc
     */
    public function redirect($url, $statusCode = 302)
    {
        $u = Url::to($url);
        if ($this->isPjaxAction) {
            Helper::data()->setSession('X-PJAX-URL', $u);
            return yii::$app->response->redirect($u, $statusCode, false);
        } else {
            return parent::redirect($u, $statusCode, true);
        }
    }

    /**
     * @inheritdoc
     */
    public function goHome()
    {
        return $this->redirect(yii::$app->getHomeUrl());
    }

    /**
     * @inheritdoc
     */
    public function goBack($defaultUrl = null)
    {
        return $this->redirect(Yii::$app->getUser()->getReturnUrl($defaultUrl));
    }

    /**
     * @inheritdoc
     */
    public function refresh($anchor = '')
    {
        return $this->redirect(yii::$app->getRequest()->getUrl() . $anchor);
    }

    /**
     * ajax validate model data
     * @param object $model
     */
    public function ajaxValidation($model)
    {
        if (yii::$app->request->isAjax && !yii::$app->request->isPjax) {
            die(Json::encode(ActiveForm::validate($model)));
        }
    }

    //@TODO: remove trash 
}
