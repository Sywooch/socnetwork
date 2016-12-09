<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserFriendsSearch */
/* @var $friends yii\data\ActiveDataProvider */
/* @var $requests yii\data\ActiveDataProvider */



$this->title = yii::$app->l->t('Manage User Friends', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('User Friends');
$this->params['pageHeader'] = yii::$app->l->t('friends');
$this->params['menu'] = Nav::CrudActions($model);
?>
<div class="user-friends-index"> 
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>



    <?=
    ListView::widget([
        'dataProvider' => $friends,
        'itemView' => 'index/_item',
        'layout' => Html::tag('ul', '{items}', [
            'class' => 'friends-list'
        ]),
    ]);
    ?>
    <?= Html::tag('div', yii::$app->l->t('friends requests'), ['class' => 'title']); ?>
    <?=
    ListView::widget([
        'dataProvider' => $requests,
        'itemView' => 'index/_item',
        'layout' => Html::tag('ul', '{items}', [
            'class' => 'friends-list'
        ]),
    ]);
    ?>


</div>
