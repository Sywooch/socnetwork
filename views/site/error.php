<?php

use app\components\extend\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
$this->params['pageHeader'] = nl2br(Html::encode($message));
?>



<div class="site-error">

    <p class="text-center">
        <?= yii::$app->l->t('The above error occurred while the Web server was processing your request.') ?>
    </p>
    <p class="text-center">
        <?= yii::$app->l->t('Please contact us if you think this is a server error. Thank you.') ?>
    </p>

</div>