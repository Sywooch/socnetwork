<?php

namespace app\models;

use Yii;
use app\models\t\SeoT;
use app\components\extend\Url;
use app\components\extend\Html;

/**
 * This is the model class for table "{{%seo}}".
 *
 * @property integer $id
 * @property string $url
 *
 * @property SeoT[] $seoT
 */
class Seo extends \app\components\extend\Model
{
    public $title;
    public $alias;
    public $keywords;
    public $description;
    public $h1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors() + [
            't' => [
                'class' => behaviors\TranslateModel::className(),
                't' => new SeoT(),
                'fk' => 'seo_id',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge([
            [['url'], 'required'],
            [['alias'], 'customUnique'],
            [['url'], 'string'],
                ], (new SeoT)->rules());
    }

    /**
     * check if alias is unique
     * @param type $attribute
     * @return type
     */
    public function customUnique($attribute)
    {
        if (SeoT::find()->where('(seo_id!=:seo_id AND alias=:alias)', ['seo_id' => $this->primaryKey, 'alias' => $this->alias])->count() > 0) {
            return $this->addError($attribute, Yii::t('yii', '{attribute} "{value}" has already been taken.', [
                                'attribute' => (new SeoT)->getAttributeLabel($attribute),
                                'value' => $this->{$attribute}
            ]));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $seoT = (new SeoT());
        return [
            'url' => yii::$app->l->t('link'),
            'alias' => $seoT->getAttributeLabel('alias'),
            'title' => $seoT->getAttributeLabel('title'),
            'h1' => $seoT->getAttributeLabel('h1'),
            'keywords' => $seoT->getAttributeLabel('keywords'),
            'description' => $seoT->getAttributeLabel('description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeoT()
    {
        return $this->hasMany(SeoT::className(), ['seo_id' => 'id']);
    }

    public function afterFind()
    {
        $af = parent::afterFind();
        if (!$this->alias) {
            $model = SeoT::findOne($this->primaryKey);
            $this->alias = $model ? $model->alias : null;
        }
        return $af;
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        $bd = parent::beforeDelete();
        foreach ($this->getSeoT()->all() as $m) {
            $m->trigger(\yii\db\BaseActiveRecord::EVENT_BEFORE_DELETE);
        }
        return $bd;
    }

    /**
     * alias link
     * @param array $options
     * @param string $label
     * @return html
     */
    public function getAliasLink($options = [], $label = false)
    {
        $admin = yii::$app->controller->module->id == 'admin';
        if ($admin) {
            $options = ['target' => '_blank', 'icon' => 'link'] + $options;
        }
        $url = Url::to([$this->alias ? $this->alias : (isset($options['href']) ? $options['href'] : null)], true);
        $options['data'] = [
            'model' => 'Seo',
            'attr' => 'alias',
            'id' => $this->primaryKey,
            'href' => $url,
            'pjax' => 0,
        ];
        if (!isset($options['title']))
            $options['title'] = $this->title;
        return Html::a($label ? $label : ($admin ? $url : $this->title), $url, $options);
    }

    /**
     * url link
     * @param array $options
     * @param string $label
     * @return html
     */
    public function getUrlLink($options = [], $label = false)
    {
        $admin = yii::$app->controller->module->id == 'admin';
        if ($admin) {
            $options = ['target' => '_blank', 'icon' => 'link'] + $options;
        }
        $url = Url::to([$this->url ? $this->url : (isset($options['href']) ? $options['href'] : null)], true);
        $options['data'] = [
            'model' => 'Seo',
            'attr' => 'url',
            'id' => $this->primaryKey,
            'href' => $url,
            'pjax' => 0,
        ];
        if (!isset($options['title']))
            $options['title'] = $this->title;
        return Html::a($label ? $label : ($admin ? $url : $this->title), $url, $options);
    }

}