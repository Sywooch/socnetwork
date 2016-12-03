<?php

use \app\components\extend\Html;
use \app\components\extend\ActiveForm;
use \app\modules\admin\components\widgets\menu\MenuTreeWidget;
use \app\components\extend\Url;
use app\modules\admin\components\widgets\icons\IconsWidget;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::tag('div', $model->getTButtons(), ['class' => 'text-right']); ?>

    <div class="row">
        <div class="col-lg-6">
            <?=
            $form->field($model, 'type')->dropDownList($model->getTypeLabels(false, false), [
                'class' => 'form-control',
                'onchange' => 'MenuTreeWidget.reloadJsTreeByType($(this),$(this).data());return false;',
                'data' => [
                    'url' => Url::to([yii::$app->controller->action->id, 'id' => $model->primaryKey]),
                    'id' => $model->primaryKey,
                ]
            ]);
            ?>
            <?=
            $form->field($model, 'visible')->dropDownList($model->getVisibleLabels(false, false), [
                'class' => 'form-control',
            ]);
            ?>
            <?= $form->field($model, 'title')->textInput(); ?>
            <?= $form->field($model, 'url')->textInput(); ?>
            <?= $form->field($model, 'active')->checkbox(['label' => $model->getAttributeLabel('active')]); ?>
        </div>
        <div class="col-lg-6">
            <?=
            $form->field($model, 'parent')->hiddenInput([
                'value' => 0
            ]);
            ?>
            <?= MenuTreeWidget::widget(['view' => 'form', 'type' => $model->type, 'model' => $model]) ?>
        </div>
    </div>


    <?= Html::label($model->getAttributeLabel('icon')); ?>
    <?= Html::tag('div', Html::label($model->getIcon()), ['class' => 'field-menu-icon']); ?>
    <?=
    Html::tag('a', yii::$app->l->t('delete') . ' ' . yii::$app->l->t('icon'), [
        'onclick' => "yii.confirm('" . yii::$app->l->t('delete') . ' ' . yii::$app->l->t('icon') . " ?',function(){
                                    $('input#menu-icon').val('');$('.field-menu-icon label').html('" . yii::$app->l->t('n/a', ['update' => false]) . "')
                            });return false;",
        'class' => 'text-danger',
        'href' => '#',
    ]);
    ?>
    <?php
    Modal::begin([
        'header' => Html::tag('h3', yii::$app->l->t('choose icon')),
        'size' => Modal::SIZE_LARGE,
        'toggleButton' => [
            'label' => yii::$app->l->t('choose icon'),
            'class' => 'btn-link'
        ]
    ]);
    ?>
    <?= Html::label($model->getAttributeLabel('icon')); ?>
    <?= $form->field($model, 'icon')->hiddenInput()->label($model->getIcon()); ?>
    <?= IconsWidget::widget(['icon' => $model->icon, 'data' => ['curent-icon-container' => '.field-menu-icon label']]); ?>
    <?php Modal::end(); ?>
    <hr/>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? yii::$app->l->t('Create') : yii::$app->l->t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
