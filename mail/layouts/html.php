<?php

use app\components\extend\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
$separator = Html::tag('div', '', ['style' => 'margin:10px 0;width:100%;border-top: 1px solid #ededed;']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <div style="margin: 5px;border: 1px solid #E8E7E7;color: #3E3E3E;min-height: 400px;font-weight: 100;">
            <?php $this->beginBody() ?>
            <div style="padding: 0 20px 20px 20px;">
                <?= Html::tag('h4', $this->title); ?>
                <?= $separator; ?>
                <?= str_replace('{separator}', $separator, $content); ?>
            </div>
            <?php $this->endBody() ?>
        </div>
    </body>
</html>
<?php $this->endPage() ?>
<a mail></a>