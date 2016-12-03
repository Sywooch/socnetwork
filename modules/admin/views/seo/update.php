<?php
use app\components\extend\Html;
use app\components\extend\Nav;

/* @var $this yii\web\View */
/* @var $model app\models\Seo */


$this->title = yii::$app->l->t('update', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('Seo'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('update');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Update Seo'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="seo-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
