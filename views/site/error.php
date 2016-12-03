<?php

use app\components\extend\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>



<div class="site-error">
    <h2 class="text-muted text-center">
        <?= nl2br(Html::encode($message)) ?>
    </h2>

    <p class="text-center">
        <?= yii::$app->l->t('The above error occurred while the Web server was processing your request.') ?>
    </p>
    <p class="text-center">
        <?= yii::$app->l->t('Please contact us if you think this is a server error. Thank you.') ?>
    </p>

</div>