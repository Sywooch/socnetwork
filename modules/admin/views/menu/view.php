<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */


$this->title = yii::$app->l->t('view', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('Menu'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('view');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('View Menu'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="menu-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => DetailView::DefaultAttributes($model, [
            'type' => [
                'attribute' => 'type',
                'value' => $model->getTypeLabels($model->type)
            ],
            'labels' => [
                [
                    'label' => yii::$app->l->t('translations'),
                    'format' => 'raw',
                    'value' => $model->getTButtons(),
                    'visible' => yii::$app->l->multi
                ]
            ],
            'parent' => [
                'attribute' => 'parent',
                'value' => $model->getParentName(),
            ],
            'url' => [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => $model->getUrlLink(['icon' => 'link', 'target' => '_blank']),
            ],
            'icon' => [
                'attribute' => 'icon',
                'format' => 'raw',
                'value' => $model->getIcon(),
            ],
            'active' => [
                'attribute' => 'active',
                'value' => $model->getActiveLabels($model->active),
            ]
                ], ['order']),
    ])
    ?>

</div>
