<?php

namespace app\models\t;

use Yii;

/**
 * This is the model class for table "{{%menu_t}}".
 *
 * @property integer $menu_id
 * @property string $title
 * @property string $url
 * @property string $language_id
 *
 * @property Menu $menu
 */
class MenuT extends \app\components\extend\Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_t}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'url'], 'required'],
            [['menu_id'], 'integer'],
            [['url'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['language_id'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $ar = [
            'menu_id' => yii::$app->l->t('menu') . $language,
            'title' => yii::$app->l->t('title') . $language,
            'url' => yii::$app->l->t('menu url') . $language,
        ];
        return parent::LanguageNoteLabels($ar) + ['language_id' => yii::$app->l->t('language')];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return array
     */
    public static function primaryKey()
    {
        return ['menu_id', 'language_id'];
    }

}