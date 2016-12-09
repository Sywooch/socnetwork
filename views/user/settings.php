<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\ListView;

/* @var $this yii\web\View */
/* @var $model \app\models\User */


$this->title = yii::$app->l->t('settings', ['update' => false]);
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHeader'] = $this->title;
?>

<?=

$this->render('settings/_form', [
    'model' => $model
])?>