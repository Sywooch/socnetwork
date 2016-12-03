<?php
/* @var $this yii\web\View */
$this->title = 'TEST';
Yii::setAlias('l', 'app/components');
?>

<?= yii::$app->l->t('update'); ?>