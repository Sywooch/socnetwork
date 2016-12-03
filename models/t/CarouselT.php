<?php

namespace app\models\t;

use Yii;
use app\components\helper\Helper;
use app\models\File;
use \app\components\extend\Html;

/**
 * This is the model class for table "{{%carousel_t}}".
 *
 * @property integer $carousel_id
 * @property string $image
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $language_id
 *
 * @property Carousel $carousel
 * @property File $file
 */
class CarouselT extends \app\components\extend\Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%carousel_t}}';
    }

    public function behaviors()
    {
        return [
            'saveFiles' => [
                'class' => \app\models\behaviors\FileSaveBehavior::className(),
                'fileAttributes' => ['image']
            ],
                ] + parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'url'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['image'], 'file', 'skipOnEmpty' => !$this->isNewRecord, 'extensions' => File::imageExtensions(true), 'maxFiles' => 1, 'maxSize' => (1024 * 1024 * 5)]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'carousel_id' => yii::$app->l->t('id'),
            'image' => yii::$app->l->t('image'),
            'title' => yii::$app->l->t('title'),
            'description' => yii::$app->l->t('description'),
            'url' => yii::$app->l->t('link'),
            'language_id' => yii::$app->l->t('language'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarousel()
    {
        return $this->hasOne(Carousel::className(), ['id' => 'carousel_id']);
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['carousel_id', 'language_id'];
    }

    /**
     * render image
     * @param type $options
     * @return string
     */
    public function renderImage($options = [])
    {
        $options['data']['url'] = $this->image;
        if (!array_key_exists('title', $options))
            $options['title'] = $this->title;
        if (!array_key_exists('alt', $options))
            $options['alt'] = $this->title;
        return $this->getFile('image')->renderImage($options);
    }

}