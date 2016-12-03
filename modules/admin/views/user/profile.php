<?php

use app\components\extend\Html;
use app\components\extend\Nav;

/* @var $this yii\web\View */
/* @var $model app\models\User */


$this->title = yii::$app->l->t('profile', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('profile');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('profile'));
?>
<div class="user-update">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
