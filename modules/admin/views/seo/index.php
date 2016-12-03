<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\Pjax;
use app\components\extend\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SeoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = yii::$app->l->t('Manage Seo', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('Seo');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Manage Seo'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="seo-index"> 
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>



    
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
            GridView::checkboxColumn(),
            'title',
            'h1',
                [
                'attribute' => 'alias',
                'value' => function($model) {
                    return $model->aliasLink;
                }
            ],
                [
                'class' => 'app\components\extend\ActionColumn',
                'template' => Html::tag('div', '{view} ' . (yii::$app->l->multi ? '{TButtons}' : '{update}') . ' {delete}', ['class' => 'grid-view-actions']),
                'buttons' => GridView::DefaultActions($model, [
                    'TButtons' => function($url, $model) {
                        return $model->getTButtons();
                    }
                ]),
            ],
        ],
    ]);
    ?>

</div>
