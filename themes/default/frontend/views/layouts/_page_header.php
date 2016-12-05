<?php

use yii\widgets\Breadcrumbs;

$menuItems = isset($this->params['menu']) ? $this->params['menu'] : [];
?>
<?php // echo Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]);  ?>

<div class="title">
    <?= (isset($this->params['pageHeader']) ? $this->params['pageHeader'] : '') ?>
</div>
