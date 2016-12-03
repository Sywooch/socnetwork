<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\Pjax;
use app\components\extend\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LanguagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = yii::$app->l->t('Manage Languages', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('Languages');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Manage Languages'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="languages-index"> 
    <?php // echo $this->render('_search', ['model' => $searchModel]);   ?>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
            GridView::checkboxColumn(),
            'language_id',
            'language_name',
                [
                'attribute' => 'language_active',
                'format' => 'raw',
                'filter' => $model->getStatusLabels(false, false),
                'filterInputOptions' => GridView::defaultOptionsForFilterDropdown(),
                'value' => function($model) {
                    return $model->getStatusLabels($model->language_active, false);
                }
            ],
                [
                'attribute' => 'language_is_default',
                'format' => 'raw',
                'filter' => $model->getDefaultLabels(false, false),
                'filterInputOptions' => GridView::defaultOptionsForFilterDropdown(),
                'value' => function($model) {
                    return $model->getDefaultLabels($model->language_is_default, false);
                }
            ],
                [
                'class' => 'app\components\extend\ActionColumn',
                'template' => Html::tag('div', '{roles} {view} {update} {delete}', ['class' => 'grid-view-actions']),
                'buttons' => GridView::DefaultActions($model),
            ],
        ],
    ]);
    ?>

</div>
