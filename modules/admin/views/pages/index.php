<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\Pjax;
use app\components\extend\GridView;
use app\components\helper\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = yii::$app->l->t('Manage Pages', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('Pages');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Manage Pages'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="pages-index"> 
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>



    
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            GridView::checkboxColumn(),
//            'title',
            [
                'label' => $model->seo->getAttributeLabel('alias'),
                'attribute' => 'alias',
                'value' => function($model) {
                    return $model->seo->getAliasLink(['href' => Helper::str()->replaceTagsWithDatatValues($model->actionUrl, $model)]);
                }
                    ],
                    [
                        'label' => $model->seo->getAttributeLabel('h1'),
                        'attribute' => 'h1',
                        'value' => function($model) {
                            return $model->seo->h1;
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => $model->getStatusLabels(false, false),
                        'filterInputOptions' => GridView::defaultOptionsForFilterDropdown(),
                        'value' => function($model) {
                            return $model->getStatusLabels($model->status);
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
