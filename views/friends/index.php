<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserFriendsSearch */
/* @var $friends yii\data\ActiveDataProvider */
/* @var $requests yii\data\ActiveDataProvider */



$this->title = yii::$app->l->t('friends', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('user friends');
$this->params['pageHeader'] = (($user->primaryKey != yii::$app->user->id) ? ' ' . yii::$app->l->t('friends of user') . ' "' . $user->fullName . '"' : yii::$app->l->t('my friends'));
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="user-friends-index"> 
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>



    <?=
    ListView::widget([
        'dataProvider' => $friends,
        'itemView' => 'index/_item',
        'viewParams' => [
            'user' => $user
        ],
        'layout' => Html::tag('ul', '{items}', [
            'class' => 'friends-list'
        ]),
    ]);
    ?>


    <?php
    if ($user->primaryKey == yii::$app->user->id) {
        echo Html::tag('div', yii::$app->l->t('friends requests'), ['class' => 'title']);
        echo ListView::widget([
            'dataProvider' => $requests,
            'itemView' => 'index/_item',
            'viewParams' => [
                'user' => $user
            ],
            'layout' => Html::tag('ul', '{items}', [
                'class' => 'friends-list'
            ]),
        ]);
    }
    ?>


</div>
