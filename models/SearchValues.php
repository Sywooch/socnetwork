<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%search_values}}".
 *
 * @property integer $search_id
 * @property string $attribute
 * @property string $value
 */
class SearchValues extends \app\components\extend\Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%search_values}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search_id', 'value','attribute'], 'required'],
            [['search_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'search_id' => yii::$app->l->t('Search ID'),
            'value' => yii::$app->l->t('Value'),
        ];
    }

}