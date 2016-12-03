<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\DetailView;
use app\models\File;

/* @var $this yii\web\View */
/* @var $model app\models\File */


$this->title = yii::$app->l->t('view', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('File'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('view');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('View File'));
$this->params['menu'] = Nav::CrudActions($model, null, [
            'update'
        ]);
?>
<div class="carousel-view"> 

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => DetailView::DefaultAttributes($model, [
            'extension' => [
                'attribute' => 'extension',
                'format' => 'raw',
                'value' => $model->renderFile()
            ],
            'location' => [
                'attribute' => 'location',
                'value' => $model->getLocationLabels($model->location)
            ],
            'owner' => [
                'attribute' => 'owner',
                'value' => $model->ownerUserName
            ],
            'destination' => [
                'attribute' => 'destination',
                'value' => $model->getDestinationList(true)
            ],
        ]),
    ])
    ?>

</div>
