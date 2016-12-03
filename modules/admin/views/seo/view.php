<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Seo */


$this->title = yii::$app->l->t('view', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('Seo'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('view');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('View Seo'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="seo-view"> 

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
            'alias'=>[
                'attribute'=>'alias',
                'format'=>'raw',
                'value'=>$model->aliasLink
            ],
            'url'=>[
                'attribute'=>'url',
                'format'=>'raw',
                'value'=>$model->urlLink
            ]
        ]),
    ])
    ?>

</div>
