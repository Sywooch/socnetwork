<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\Pjax;
use app\components\extend\GridView;
use app\models\File;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\FileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \app\models\File */

$this->title = yii::$app->l->t('Manage File', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('File');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Manage File'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="carousel-index"> 
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>



    
    <?php
    echo Html::tag('div', yii::$app->l->t('total file size {size}', ['size' => $model->getFilesSize()]), ['class' => 'text-info']);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model) {
            $options = [
                'class'=>$model->status == File::STATUS_DELETED ? 'deleted' : ''
            ];
            return $options;
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            GridView::checkboxColumn(),
            [
                'attribute' => 'extension',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->renderFile(['size' => File::SIZE_SM]);
                },
                    ],
                    'title',
                    [
                        'attribute' => 'location',
                        'filter' => $model->getLocationLabels(),
                        'filterInputOptions' => GridView::defaultOptionsForFilterDropdown(),
                        'value' => function($model) {
                            return $model->getLocationLabels($model->location);
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => 'date',
                    ],
                    [
                        'attribute' => 'size',
                        'value' => 'formattedSize',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'owner',
                        'value' => function($model) {
                            return $model->ownerUserName;
                        },
                    ],
                    [
                        'attribute' => 'destination',
                        'value' => function($model) {
                            return $model->getDestinationList(true);
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function($model) {
                            return $model->getStatusLabels($model->status);
                        },
                        'filter' => $model->getStatusLabels(),
                        'filterInputOptions' => GridView::defaultOptionsForFilterDropdown(),
                    ],
                    [
                        'class' => 'app\components\extend\ActionColumn',
                        'template' => Html::tag('div', '{view} {downloadFile} {delete}', ['class' => 'grid-view-actions']),
                        'buttons' => GridView::DefaultActions($model, [
                            'downloadFile' => function($url, $model) {
                                return $model->getDownloadLink();
                            }
                        ]),
                    ],
                ],
            ]);
            ?>
            
</div>
