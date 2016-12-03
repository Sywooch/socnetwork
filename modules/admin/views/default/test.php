<?php

use app\components\extend\Html;

$this->title = 'test';
?>

<?= Html::tag('h1', Html::encode($this->title), ['class' => 'h1']); ?>


<?php 
//echo yii::t('app','a');
?>