<?php

use app\components\extend\Html;
use app\components\extend\Nav;

/* @var $this yii\web\View */
/* @var $model app\models\Languages */


$this->title = yii::$app->l->t('Update', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('Languages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('update');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Update Languages'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="languages-update">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
