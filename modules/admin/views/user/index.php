<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\Pjax;
use app\components\extend\GridView;
use app\models\File;
use app\components\extend\Url;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = yii::$app->l->t('Manage User', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('Users');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Manage User'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="user-index"> 
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            GridView::checkboxColumn(),
            [
                'attribute' => 'avatar',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->renderAvatar();
                }
            ],
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'rbacRole',
                'value' => function($model, $key, $index, $column) {
                    return $model->assignedRoles();
                },
                'filter' => $model->getAvailableRoles(),
                'filterInputOptions' => GridView::defaultOptionsForFilterDropdown(),
            ],
            [
                'attribute' => 'status',
                'value' => function($model, $key, $index, $column) {
                    return $model->getStatusLabels($model->status);
                },
                'filter' => $model->getStatusLabels(false, false),
                'filterInputOptions' => GridView::defaultOptionsForFilterDropdown(),
            ],
            [
                'class' => 'app\components\extend\ActionColumn',
                'template' => Html::tag('div', '{roles} {login} {view} {update} {delete}', ['class' => 'grid-view-actions']),
                'buttons' => GridView::DefaultActions($model, [
                    'roles' => function($url, $model, $key) {
                        return yii::$app->user->can('rbac-assignment') ? $model->getRolesButton() : '';
                    },
                    'login' => function($url, $model, $key) {
                        return yii::$app->user->can('site-authuserbyid', ['module' => 'frontend']) ? Html::a('', ['/site/auth-user-by-id', 'id' => $key], [
                                    'icon' => 'sign-in',
                                    'title' => yii::$app->l->t('sign in as "{username}"', [
                                        'username' => $model->fullName,
                                    ]),
                                    'target' => '_blank',
                                    'data' => [
                                        'pjax' => 0
                                    ]
                                ]) : '';
                    }
                        ]),
                    ],
                ],
            ]);
            ?>
            
        </div>
        <?= \app\modules\admin\components\widgets\rbac\manageUserRoles\manageUserRoles::widget(); ?>