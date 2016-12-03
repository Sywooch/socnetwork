<?php

//@TODO: test frontend 
/* @var $this yii\web\View */

use app\components\extend\Html;
use app\components\helper\Helper;
use app\components\widgets\gallery\GalleryWidget;
use app\components\widgets\uploader\UploaderWidget;
use app\components\extend\ActiveForm;

$this->title = 'test';
$this->params['breadcrumbs'][] = Helper::data()->getParam('h1', $this->title);
$this->params['pageHeader'] = Html::tag('h1', Helper::data()->getParam('h1', $this->title));
$text = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.';

   

   

?>
