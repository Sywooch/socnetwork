<?php

namespace app\models;

use \Yii;
use \app\components\extend\Html;
use \app\components\extend\Url;
use \app\components\helper\Helper;

/**
 * This is the model class for table "languages".
 *
 * @property string $language_id
 * @property string $language_name
 * @property integer $language_active
 * @property integer $language_is_default
 */
class Languages extends \app\components\extend\Model
{

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;
    const IS_DEFAULT = 1;
    const IS_NOT_DEFAULT = 0;

    public $multi;
    public $default;
    public $liveEditT = true;
    public $allLanguages;

    public function init()
    {
        parent::init();
        $this->default = Helper::data()->getCookie('language', null);
    }

    public function changeLanguage($id)
    {
        return Helper::data()->setCookie('language', $id);
    }

    /**
     * gel all languages
     * @return array
     */
    public function getLanguages()
    {
        if ($this->allLanguages)
            return $this->allLanguages;
        $models = self::find()->where(['language_active' => self::STATUS_ACTIVE])->orderBy(['language_is_default' => SORT_DESC])->all();
        if ($models) {
            $this->allLanguages = $models;
            foreach ($models as $m) {
                $this->languages[$m->language_id] = $m->language_name;
                if (!$this->default && $m->language_is_default === self::IS_DEFAULT) {
                    $this->default = $m->language_id;
                }
            }
            if (count($this->languages) > 1) {
                $this->multi = true;
            } else {
                $this->default = $m->language_id;
                $this->languages[$this->default] = $this->default;
                $this->allLanguages = $this->languages;
            }
        } else {
            $this->default = yii::$app->language;
            $this->languages[$this->default] = $this->default;
            $this->allLanguages = $this->languages;
        }
        if (array_key_exists($this->default, $this->languages))
            return [$this->default => $this->languages[$this->default]] + $this->languages;
        return $this->languages;
    }

    /**
     * @param int $status
     * @param boolean $withLiveEdit (return translated labels wrapped in html tag if TRUE)
     * @return array/string
     */
    public function getStatusLabels($status = false, $withLiveEdit = true)
    {
        $ar = [
            self::STATUS_ACTIVE => yii::$app->l->t('language active', ['update' => $withLiveEdit]),
            self::STATUS_DISABLED => yii::$app->l->t('language disabled', ['update' => $withLiveEdit]),
        ];
        return $status !== false ? $ar[$status] : $ar;
    }

    /**
     * @param int $default
     * @param boolean $withLiveEdit (return translated labels wrapped in html tag if TRUE)
     * @return array/string
     */
    public function getDefaultLabels($default = false, $withLiveEdit = true)
    {
        $ar = [
            self::IS_DEFAULT => yii::$app->l->t('yes', ['update' => $withLiveEdit]),
            self::IS_NOT_DEFAULT => yii::$app->l->t('no', ['update' => $withLiveEdit])
        ];
        return $default !== false ? $ar[$default] : $ar;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%languages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['language_id', 'language_name'], 'required'],
                [['language_id', 'language_name'], 'unique'],
                [['language_active'], 'integer'],
                [['language_active'], 'default', 'value' => self::STATUS_ACTIVE],
                [['language_is_default'], 'integer'],
                [['language_id'], 'string', 'max' => 2],
                [['language_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language_id' => self::t('id'),
            'language_name' => self::t('name'),
            'language_active' => self::t('status'),
            'language_is_default' => self::t('language is default'),
        ];
    }

    /**
     * @param string $message
     * @param array $params
     * @param string $language
     * @return string
     */
    public function t($message, $params = [], $language = null)
    {
        if (!isset($params['update'])) {
            $params['update'] = true;
        }
        if (!isset($params['ucf'])) {
            $params['ucf'] = true;
        }

        $t = yii::t((array_key_exists('category', $params) ? $params['category'] : 'app'), mb_strtolower($message, 'UTF-8'), $params, $language);

        if ((is_array($params) && (array_key_exists('ucf', $params)) || !array_key_exists('lcf', $params)) || $params == 'ucf')
            $t = self::mb_ucfirst($t);
        if ((is_array($params) && array_key_exists('lcf', $params)) || $params == 'lcf')
            $t = self::mb_lcfirst($t);
        $this->liveEditT = false;
        $r = ($this->liveEditT && isset($params['update']) && $params['update'] === true) ? Html::tag('span', $t . ' ' . Html::ico('pencil', [
                            'class' => 'text-succes',
                        ]), [
                    'class' => 'label label-success',
                    'onclick' => 'yii.mes("mes");'
                ]) : $t;
        return $r;

//        $('*').bind('blur change click dblclick error focus focusin focusout hover keydown keypress keyup load mousedown mouseenter mouseleave mousemove mouseout mouseover mouseup resize scroll select submit', function(event){
//    event.preventDefault();
//});
        //@TODO: translation live edit 
    }

    /**
     * if system does not found translation it will be inserted into the db
     * @param array $event contains info about the translation: message,category,language,cache..
     * @return boolean
     */
    public static function insertMissingTranslationIntoDb($event)
    {
        $m = $event->message;
        $c = $event->category;
        $l = $event->language;
        $source = I18nMessageSource::find()->where('message=:m AND category=:c', ['m' => strtolower($m), 'c' => strtolower($c),])->one();
        if (!$source) {
            $source = new I18nMessageSource();
            $source->category = $c;
            $source->message = $m;
            $source->save();
        }
        if ($source) {
            $translation = new I18nMessage();
            $translation->translation = $m;
            $translation->language = $l;
            $translation->id = $source->id;
            if ($translation->validate() && $translation->save())
                return true;
        }
    }

    /**
     * Uppercase first letter from word/text
     * @word - word/text (string)
     * @returns -  transformed text/word (string)
     */
    public static function mb_ucfirst($word)
    {
        return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($word, 1, mb_strlen($word), 'UTF-8');
    }

    /**
     * Lowercase first letter from word/text
     * @word - word/text (string)
     * @returns -  transformed text/word (string)
     */
    public static function mb_lcfirst($word)
    {
        return mb_strtolower(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($word, 1, mb_strlen($word), 'UTF-8');
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->setDefault($insert);
        }
        return true;
    }

    /**
     * clear old defaults
     * @param type $insert
     */
    public function setDefault($insert)
    {
        $isDefault = $this->language_is_default == self::IS_DEFAULT;
        if (($insert && $isDefault) || ($isDefault && $this->oldAttributes['language_is_default'] == self::IS_NOT_DEFAULT)) {
            self::updateAll(['language_is_default' => self::IS_NOT_DEFAULT], ['language_is_default' => self::IS_DEFAULT]);
        }
    }

    /**
     * get active language items for nav
     * @return array
     */
    public function getMenuLanguages()
    {
        if (!yii::$app->l->multi)
            return [];
        $items = [];
        foreach ($this->allLanguages as $k => $v) {
            $items[] = [
                'label' => ($v->language_name && $v->language_name != '') ? $v->language_name : $v->language_id,
                'url' => Url::to(['/site/change-language', 'id' => $v->language_id]),
                'active' => ($v->language_id === yii::$app->language),
            ];
        }
        return count($items) > 0 ? [
                [
                'label' => yii::$app->language,
                'items' => $items,
                'visible' => yii::$app->l->multi
            ]
                ] : [];
    }

}
