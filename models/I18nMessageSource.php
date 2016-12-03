<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%i18n_message_source}}".
 *
 * @property integer $id
 * @property string $message
 * @property string $category
 * @property integer $is_new
 *
 * @property I18nMessage[] $i18nMessages
 */
class I18nMessageSource extends \app\components\extend\Model
{
    public $CDeleteAllTrigger = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%i18n_message_source}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => yii::$app->lang->t('db', 'ID'),
            'message' => yii::$app->lang->t('db', 'Message'),
            'category' => yii::$app->lang->t('db', 'Category'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getI18nMessages()
    {
        return $this->hasMany(I18nMessage::className(), ['id' => 'id']);
    }

}