<?php
use app\components\extend\Html;
use app\components\extend\Nav;

/* @var $this yii\web\View */
/* @var $model app\models\File */
 

$this->title = yii::$app->l->t('create', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('File'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('create');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Create file'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="carousel-create"> 
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
