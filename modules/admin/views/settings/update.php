<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\ActiveForm;
use app\models\Settings;
use app\components\extend\extensions\Redactor;
use app\components\extend\Url;

/* @var $this yii\web\View */


$this->title = yii::$app->l->t('update', ['update' => false]);
if ($modelName != 'Settings') {
    $this->params['breadcrumbs'][] = ['label' => yii::$app->l->t($modelName), 'url' => ['/admin/' . lcfirst($modelName) . '/index']];
}
$this->params['breadcrumbs'][] = yii::$app->l->t('Settings');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Update Settings'));

$options = [
    'class' => 'form-control'
];
$containerOptions = [
    'class' => 'col-md-6'
];
?>
<div class="settings-update">
    <?= Html::beginForm(); ?>
    <?php
    $tmp = '';
    foreach ($settings as $k => $v):

        $fieldOptions = array_key_exists('options', $v) ? array_merge($options, $v['options']) : $options;
        $after = array_key_exists('after', $v) ? Html::tag('div', $v['after'], ['style' => 'padding-right: 15px;padding-left: 15px;']) : '';
        $before = array_key_exists('before', $v) ? Html::tag('div', $v['before'], ['style' => 'padding-right: 15px;padding-left: 15px;']) : '';
        $tmp .= $before;
        $containerOptions = array_key_exists('containerOptions', $v) ? array_merge($containerOptions, $v['containerOptions']) : $containerOptions;
        $fieldOptions['id'] = $k;
        $fieldName = "Settings[$modelName][$k]";
        $lable = Html::label($v['label'], $fieldOptions['id'], ['class' => 'control-label']);
        switch ($v['field']) {
            case (Settings::FIELD_DROPDOWN):
                $tmp .= Html::tag('div', $lable . Html::dropDownList($fieldName, $v['value'], $v['items'], $fieldOptions), $containerOptions);
                break;
            case (Settings::FIELD_RADIO_LIST):
                Html::removeCssClass($fieldOptions, 'form-control');
                $tmp .= Html::tag('div', $lable . Html::radioList($fieldName, $v['value'], $v['items'], $fieldOptions), $containerOptions);
                break;
            case (Settings::FIELD_CHECKBOX):
                Html::removeCssClass($fieldOptions, 'form-control');
                $tmp .= Html::hiddenInput($fieldName, 0, $v['items']);
                $tmp .= Html::tag('div', $lable . Html::checkbox($fieldName, $v['value'], $v['items'], $fieldOptions), $containerOptions);
                break;
            case (Settings::FIELD_CHECKBOX_LIST):
                Html::removeCssClass($fieldOptions, 'form-control');
                $tmp .= Html::tag('div', $lable . Html::checkboxList($fieldName, $v['value'], $v['items'], $fieldOptions), $containerOptions);
                break;
            case (Settings::FIELD_TEXTAREA):
                $tmp .= Html::tag('div', $lable . Html::textarea($fieldName, $v['value'], $fieldOptions), $containerOptions);
                break;
            case (Settings::FIELD_TEXTAREA_REDACTOR):
                $tmp .= Html::tag('div', $lable . Redactor::widget(array_merge(['name' => $fieldName, 'value' => $v['value']], Redactor::getDefaultSettings())), $containerOptions);
                break;
            default :
                $tmp .= Html::tag('div', $lable . Html::{$v['field']}($fieldName, $v['value'], $fieldOptions), $containerOptions);
                break;
        }
        $tmp .= $after;
        ?>
    <?php endforeach; ?>
    <?= Html::tag('div', $tmp, ['class' => 'row']); ?>
    <hr/>
    <?= Html::submitButton(yii::$app->l->t('save'), ['class' => 'btn btn-primary']); ?>
    <?=
    Html::a(yii::$app->l->t('restore default'), '#', [
        'class' => 'btn btn-warning ' . ($model->settingsAreChanged ? '' : 'hidden'),
        'onclick' => 'Settings.Update.restoreDefaultSettings($(this));return false;',
        'data' => [
            'confirm-message' => yii::$app->l->t('restore defaults') . ' ?',
            'url' => Url::to([
                'update',
                'm' => $modelName
            ])
        ]
    ]);
    ?>
    <?= Html::endForm(); ?>
</div>