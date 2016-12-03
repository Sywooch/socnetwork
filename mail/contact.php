<?php

use app\components\extend\Html;
use app\components\helper\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\forms\ContactForm */
?>


<?php
$this->title = yii::$app->l->t('new message from user on {sitename}', ['sitename' => yii::$app->name]);
echo Html::tag('i', $model->getAttributeLabel('name') . ' : ' . $model->name) . Html::tag('br');
echo Html::tag('i', $model->getAttributeLabel('email') . ' : ' . Html::a($model->email, 'mailto:' . $model->email));
echo '{separator}';
echo Html::tag('h4', $model->subject);
echo Html::tag('p', $model->body);