<?php

namespace app\models\t;

use Yii;

/**
 * This is the model class for table "{{%pages_t}}".
 *
 * @property integer $page_id
 * @property string $title
 * @property string $content
 * @property string $language_id
 */
class PagesT extends \app\components\extend\Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages_t}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['page_id'], 'integer'],
            [['content', 'title'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $ar = [
            'page_id' => yii::$app->l->t('page'),
            'title' => yii::$app->l->t('title'),
            'content' => yii::$app->l->t('content'),
        ];

        return parent::LanguageNoteLabels($ar) + ['language_id' => yii::$app->l->t('language')];
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['page_id', 'language_id'];
    }

}