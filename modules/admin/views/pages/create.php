<?php
use app\components\extend\Html;
use app\components\extend\Nav;

/* @var $this yii\web\View */
/* @var $model app\models\Pages */
 

$this->title = yii::$app->l->t('create', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('create');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Create Pages'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="pages-create"> 
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
