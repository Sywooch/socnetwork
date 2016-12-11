<?php

namespace app\controllers;

use yii;
use app\components\FrontendController;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\forms\ContactForm;
use app\models\forms\SignupForm;
use app\models\forms\PasswordResetRequestForm;
use app\models\forms\ResetPasswordForm;
use yii\helpers\Json;
use app\components\extend\ActiveForm;
use yii\web\BadRequestHttpException;
use app\models\File;
use app\models\User;
use app\components\helper\Helper;

class SiteController extends FrontendController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $this->addAllowedActions(['auth-user-by-id']);
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => '\app\components\extend\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
//                'backColor' => '0x333333',
                'foreColor' => '736146',
                'transparent' => true,
                'fixedVerifyCode' => YII_ENV_DEV ? '1' : null,
            ],
        ];
    }

    public function actionTest($t = null)
    {

        if ($t && $files = \app\models\File::find()->all()) {
            foreach ($files as $file) {
                $file->uploadToFtp();
            }
            die();
        }

        $model = new \app\models\forms\TestForm();
        \app\components\widgets\uploader\UploaderWidget::manage([
//            'model' => $model,
//            'attribute' => 'image'
            'name' => 'image'
        ]);

        return $this->render('test', [
                    'model' => $model
        ]);
    }

    public function actionIndex()
    {
        if (!yii::$app->user->isGuest) {
            return $this->redirect(['/user/index']);
        }
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->contact(Yii::$app->params['adminEmail'])) {
                $this->setMessage('success');
                return $this->refresh();
            }
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    public function actionSignup($ref = null)
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->referral = (int) Helper::str()->b64Decode($ref);
            $this->ajaxValidation($model);
            if ($user = $model->signup()) {
                if (yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $this->setMessage('success', yii::$app->l->t('check your email for further instructions.'));
                return $this->goHome();
            } else {
                $this->setMessage('error', yii::$app->l->t('sorry, we are unable to reset password for email provided.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            $this->setMessage('success', yii::$app->l->t('new password was saved.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    public function actionDownload($file)
    {
        $model = File::find()->where(['name' => $file, 'status' => File::STATUS_UPLOADED])->one();
        /* @var $model File */
        if ($model) {
            $model->download();
        } else {
            throw new \yii\web\NotFoundHttpException(yii::$app->l->t('file not found'));
        }
    }

    public function actionAuthUserById($id)
    {
        Yii::$app->user->login(User::find()->where('id=:id', ['id' => (int) $id])->one());
        return $this->redirect(yii::$app->homeUrl);
    }

}
