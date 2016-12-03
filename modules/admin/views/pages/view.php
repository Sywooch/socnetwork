<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\DetailView;
use app\components\helper\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\Pages */


$this->title = yii::$app->l->t('view', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('view');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('View Pages'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="pages-view"> 
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => DetailView::DefaultAttributes($model, [
            'labels' => [
                    [
                    'label' => yii::$app->l->t('translations'),
                    'format' => 'raw',
                    'value' => $model->getTButtons(),
                    'visible' => yii::$app->l->multi
                ]
            ],
            'content' => [
                'attribute' => 'content',
                'format' => 'raw',
            ],
            'status' => [
                'label' => $model->getAttributeLabel('status'),
                'attribute' => 'status',
                'value' => $model->getStatusLabels($model->status),
            ],
        ]) + DetailView::DefaultAttributes($seo = $model->seo, [
            'alias' => [
                'label' => $model->seo->getAttributeLabel('alias'),
                'attribute' => 'alias',
                'format' => 'raw',
                'value' => $seo->getAliasLink(['href' => Helper::str()->replaceTagsWithDatatValues($model->actionUrl, $model)])
            ]], ['url']),
    ])
    ?>

</div>
