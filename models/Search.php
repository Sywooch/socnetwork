<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%search}}".
 *
 * @property string $id
 * @property string $link
 * @property string $params
 * @property string $model
 * @property string $language_id
 */
class Search extends \app\components\extend\Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%search}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link', 'params', 'model', 'language_id'], 'required'],
            [['model'], 'string', 'max' => 200],
            [['language_id'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'link' => yii::$app->l->t('mink'),
            'model' => yii::$app->l->t('model'),
            'language_id' => yii::$app->l->t('language'),
        ];
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValues()
    {
        return $this->hasMany(SearchValues::className(), ['search_id' => 'id']);
    }

    public function afterFind()
    {
        $af = parent::afterFind();
        $this->params = (object) unserialize($this->params);
        return $af;
    }

}