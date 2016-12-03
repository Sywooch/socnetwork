<?php
/* @var $this yii\web\View */

use app\components\extend\Html;
use app\components\helper\Helper;

$this->title = yii::$app->l->t('search', ['update' => false]);
?>

<?= \app\components\widgets\search\SearchWidget::widget(['view' => 'results']); ?>

