<?php

use app\components\extend\Html;
use \yii\rbac\Item;

$this->title = yii::$app->l->t('RBAC', ['update' => false]);
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('rbac'));
?>

<div class="row">
    <div class="col-md-6">
        <?= Html::tag('h4', yii::$app->l->t('user roles')); ?>
        <?=
        \app\modules\admin\components\widgets\rbac\manager\rbacManager::widget([
            'type' => Item::TYPE_ROLE,
        ]);
        ?>
    </div>

    <div class="col-md-6">
        <?= Html::tag('h4', yii::$app->l->t('role permissions')); ?>       
        <?=
        \app\modules\admin\components\widgets\rbac\manager\rbacManager::widget([
            'type' => Item::TYPE_PERMISSION,
        ]);
        ?>
    </div>
</div>
