<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */


$this->title = yii::$app->l->t('View', ['update' => false]);
$this->params['breadcrumbs'][] = ['label' => yii::$app->l->t('User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::$app->l->t('view');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('View User'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="user-view">
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => DetailView::DefaultAttributes($model, [
            'avatar' => [
                'attribute' => 'avatar',
                'format' => 'raw',
                'value' => $model->renderAvatar(['size' => \app\models\File::SIZE_MD])
            ],
            'rbacRole' => [
                'attribute' => 'role',
                'format' => 'raw',
                'value' => $model->assignedRoles()
            ],
            'balance' => [
                'attribute' => 'balance',
                'format' => 'raw',
                'value' => $model->getBalance()
            ],
            'status' => [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->getStatusLabels($model->status)
            ],
                ], ['password', 'role']),
    ]);
    ?>
</div>