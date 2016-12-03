<?php
$languageActive = ($controller == 'languages');
$canLanguages = yii::$app->user->can('languages-index');

return [
    'label' => yii::$app->l->t('languages'),
    'linkOptions' => ['icon' => 'globe'],
    'url' => ['/admin/languages/index'],
    'visible' => $canLanguages,
    'active' => $languageActive
];
