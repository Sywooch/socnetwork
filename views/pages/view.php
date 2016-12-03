<?php

use app\components\extend\Html;
use app\components\extend\ActiveForm;
use yii\captcha\Captcha;
use app\components\helper\Helper;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = $model->title;

$this->params['breadcrumbs'][] = Helper::data()->getParam('h1', $model->title);
$this->params['pageHeader'] = Html::tag('h1', Helper::data()->getParam('h1', $model->title));
?>

<?= $model->content ?>

