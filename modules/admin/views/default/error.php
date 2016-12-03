<?php

use app\components\extend\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
$this->params['pageHeader'] = Html::tag('h1', Html::encode($this->title));
?>
<div class="site-error">
    <?= Html::tag('div', nl2br(Html::encode($message)), ['class' => 'alert alert-danger']) ?>
</div>
