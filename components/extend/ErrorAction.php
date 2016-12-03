<?php

namespace app\components\extend;

use yii;
use yii\web\ErrorAction as BaseErrorAction;
use app\components\extend\Html;
use yii\web\HttpException;
use yii\base\UserException;

class ErrorAction extends BaseErrorAction
{

    /**
     * Runs the action
     *
     * @return string result content
     */
    public function run()
    {
        if (($exception = yii::$app->getErrorHandler()->exception) === null) {
            // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
            $exception = new HttpException(404, yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = $this->defaultName ?: yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = $this->defaultMessage ?: yii::t('yii', 'An internal server error occurred.');
        }

        if (yii::$app->getRequest()->getIsAjax() && !yii::$app->controller->isPjaxAction) {
            return "$name: $message";
        } else {
            return $this->controller->render($this->view ?: $this->id, [
                        'name' => $name,
                        'message' => $message,
                        'exception' => $exception,
            ]);
        }
    }

}
