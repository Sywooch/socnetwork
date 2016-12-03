<?php
return [
    'translations' => [
        'app' => [
            'on missingTranslation' => array('app\models\Languages', 'insertMissingTranslationIntoDb'),
            'class' => 'yii\i18n\DbMessageSource',
            'sourceLanguage' => 'en',
            'sourceMessageTable' => '{{%i18n_message_source}}',
            'messageTable' => '{{%i18n_message}}'
        ],
    ],
];
