<?php

use app\components\extend\Html;
use app\components\extend\Nav;

/* @var $this yii\web\View */
/* @var $model app\models\User */


$this->title = yii::$app->l->t('Create', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('create');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Create User'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="user-create"> 
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
