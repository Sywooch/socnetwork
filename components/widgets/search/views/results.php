<?php

use app\components\extend\ListView;
use app\components\extend\Html;

yii::$app->view->params['breadcrumbs'][] = yii::$app->l->t('search');
yii::$app->view->params['breadcrumbs'][] = $post;
$noResults = Html::tag('p', yii::$app->l->t('No results'), ['class' => 'text-danger']);
?>

<?=
$dataProvider ? ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => 'results/_default_item',
            'layout' => '{items}',
            'emptyText' => $noResults
        ]) : $noResults;
?>