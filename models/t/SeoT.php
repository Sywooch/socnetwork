<?php

namespace app\models\t;

use Yii;
use \app\models\Seo;
use app\components\helper\Helper;

/**
 * This is the model class for table "{{%seo_t}}".
 *
 * @property integer $seo_id
 * @property string $alias
 * @property string $title
 * @property string $h1
 * @property string $keywords
 * @property string $description
 * @property string $language_id
 *
 * @property Seo $seo
 */
class SeoT extends \app\components\extend\Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_t}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'updateSeoLinks' => [
                'class' => \app\models\behaviors\UpdateSeoLinksBehavior::className(),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias'], 'required'],
            [['alias', 'h1', 'keywords', 'description'], 'string'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $ar = [
            'seo_id' => yii::$app->l->t('seo'),
            'alias' => yii::$app->l->t('alias'),
            'title' => yii::$app->l->t('title'),
            'h1' => yii::$app->l->t('h1'),
            'keywords' => yii::$app->l->t('keywords'),
            'description' => yii::$app->l->t('description'),
            'language_id' => yii::$app->l->t('language'),
        ];

        return parent::LanguageNoteLabels($ar);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::className(), ['id' => 'seo_id']);
    }

    public static function primaryKey()
    {
        return ['seo_id', 'language_id'];
    }

    public function setMetaData($controller)
    {


        if (trim($this->title) != '') {
            Helper::data()->setParam('pageTitle', $this->title);
        }
        if (trim($this->description) != '') {
            Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => $this->description,
            ]);
        }
        if (trim($this->keywords) != '') {
            Yii::$app->view->registerMetaTag([
                'name' => 'keywords',
                'content' => $this->keywords,
            ]);
        }

        if (trim($this->h1) != '') {
            Helper::data()->setParam('h1', $this->h1);
        }
    }

}