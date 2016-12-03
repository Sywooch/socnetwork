<?php

use \app\components\extend\Html;
use \app\components\extend\Nav;
use \app\components\extend\Pjax;
use \app\components\extend\GridView;
use \app\modules\admin\components\widgets\menu\MenuTreeWidget;
use \app\components\extend\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = yii::$app->l->t('Manage Menu', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('Menu');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Manage Menu'));
$this->params['menu'] = Nav::CrudActions($model, [], ['delete_all'], true);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="btn-group">
            <?php foreach ($model->getTypeLabels() as $t => $l): ?>
                <?=
                Html::a($l, Url::to(['index', 'type' => $t]), [
                    'class' => 'btn btn-default btn-sm ' . ($t == $type ? 'active' : '')
                ]);
                ?>
            <?php endforeach; ?>
        </div>
        <div class="clearfix"></div>
        <?= MenuTreeWidget::widget(['type' => $type]) ?>
    </div>
</div>
