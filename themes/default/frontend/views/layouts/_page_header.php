<?php

use yii\widgets\Breadcrumbs;

$menuItems = isset($this->params['menu']) ? $this->params['menu'] : [];
?>
<?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]); ?>
<?= (isset($this->params['pageHeader']) ? $this->params['pageHeader'] : '') ?>
   