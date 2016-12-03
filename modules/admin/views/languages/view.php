<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Languages */


$this->title = yii::$app->l->t('View', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('Languages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('view');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('View Languages'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="languages-view"> 

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => DetailView::DefaultAttributes($model, [
            'language_active' => [
                'attribute' => 'language_active',
                'format'=>'raw',
                'value' => $model->getStatusLabels($model->language_active),
            ],
            'language_is_default' => [
                'attribute' => 'language_is_default',
                'value' => $model->getDefaultLabels($model->language_is_default),
            ]
        ]),
    ])
    ?>

</div>
