<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%i18n_message}}".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 *
 * @property I18nMessageSource $id0
 */
class I18nMessage extends \app\components\extend\Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%i18n_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id', 'is_new'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => yii::$app->lang->t('db', 'ID'),
            'language' => yii::$app->lang->t('db', 'Language'),
            'translation' => yii::$app->lang->t('db', 'Translation'),
            'is_new' => yii::$app->lang->t('db', 'Is New'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(I18nMessageSource::className(), ['id' => 'id']);
    }

}