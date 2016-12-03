<?php

namespace app\models;

use Yii;
use app\components\extend\Html;
use app\components\extend\Url;
use app\components\helper\Helper;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $parent
 * @property integer $order
 * @property integer $active
 * @property integer $visible
 * @property string $icon
 */
class Menu extends \app\components\extend\Model
{
    /**
     * @var translation variabiles & translation model class
     */
    public $title;
    public $url;

    const TYPE_MAIN = 1;
    const TYPE_ASIDE = 2;
    const TYPE_FOOTER = 3;
    const ACTIVE = 1;
    const ACTIVE_FALSE = 0;
    const VISIBLE_FOR_ALL_USERS = 1;
    const VISIBLE_FOR_GUESTS = 2;
    const VISIBLE_FOR_AUTHORIZED_USERS = 3;

    /**
     * @param integer $visible
     * @param boolean $withLiveEdit (return translated labels wrapped in html tag if TRUE)
     * @return array/string
     */
    public function getVisibleLabels($visible = false, $withLiveEdit = true)
    {
        $ar = [
            self::VISIBLE_FOR_ALL_USERS => yii::$app->l->t('visible for all users', ['update' => $withLiveEdit]),
            self::VISIBLE_FOR_AUTHORIZED_USERS => yii::$app->l->t('visible for authorized users', ['update' => $withLiveEdit]),
            self::VISIBLE_FOR_GUESTS => yii::$app->l->t('visible for guests', ['update' => $withLiveEdit]),
        ];
        return $visible === false ? $ar : $ar[$visible];
    }

    /**
     * @param integer $active
     * @param boolean $withLiveEdit (return translated labels wrapped in html tag if TRUE)
     * @return array/string
     */
    public function getActiveLabels($active = false, $withLiveEdit = true)
    {
        $ar = [
            self::ACTIVE => yii::$app->l->t('item is active', ['update' => $withLiveEdit]),
            self::ACTIVE_FALSE => yii::$app->l->t('item is not active', ['update' => $withLiveEdit]),
        ];
        return $active === false ? $ar : $ar[$active];
    }

    /**
     * @param integer $type
     * @param boolean $withLiveEdit (return translated labels wrapped in html tag if TRUE)
     * @return array/string
     */
    public function getTypeLabels($type = false, $withLiveEdit = true)
    {
        $ar = [
            static::TYPE_MAIN => yii::$app->l->t('main menu', ['update' => $withLiveEdit]),
            static::TYPE_ASIDE => yii::$app->l->t('aside menu', ['update' => $withLiveEdit]),
            static::TYPE_FOOTER => yii::$app->l->t('footer menu', ['update' => $withLiveEdit]),
        ];

        return $type === false ? $ar : $ar[$type];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors() + [
            't' => [
                'class' => behaviors\TranslateModel::className(),
                't' => new \app\models\t\MenuT(),
                'fk' => 'menu_id',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'parent', 'url'], 'required'],
            [['type', 'active', 'visible', 'parent', 'order'], 'integer'],
            [['parent', 'order'], 'default', 'value' => 0],
            [['icon', 'title'], 'string'],
            [['active'], 'default', 'value' => self::ACTIVE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $ar = [
            'title' => yii::$app->l->t('title'),
            'url' => yii::$app->l->t('url'),
        ];
        return parent::LanguageNoteLabels($ar) + [
            'type' => yii::$app->l->t('type'),
            'parent' => yii::$app->l->t('item parent'),
            'order' => yii::$app->l->t('item order'),
            'active' => yii::$app->l->t('item is active'),
            'icon' => yii::$app->l->t('icon'),
        ];
    }

    /**
     * 
     * @param array $options
     * @return string
     */
    public function getIcon($options = [])
    {
        if ($this->icon && trim($this->icon) != '') {
            return Html::ico($this->icon, $options);
        }
        return yii::$app->l->t('n/a');
    }

    /**
     * get menu item parent
     * @param mixed $noParent (returns alternate value in case of no parent)
     * @return mixed
     */
    public function getParent($noParent = null)
    {
        $parent = self::find()->where([
                    'id' => $this->parent
                ])->one();
        return (!$parent && $noParent) ? $noParent : $parent;
    }

    /**
     * return parent name
     */
    public function getParentName()
    {
        $parent = $this->getParent(yii::$app->l->t('root'));
        return is_string($parent) ? $parent : $parent->title;
    }

    /**
     * return children of the itemm
     * @return mixed
     */
    public function getChildren()
    {
        return self::find()->where([
                    'parent' => $this->primaryKey
                ])->all();
    }

    /**
     * delete item children
     */
    public function deleteChildren()
    {
        $children = $this->getChildren();
        if ($children) {
            foreach ($children as $c)
                $c->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteChildren();
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->checkUrl();
    }

    /**
     * if url not fount on some language system will find any url
     */
    public function checkUrl()
    {
        if (!$this->url) {
            $modelT = $this->t
                    ->find()
                    ->where(['menu_id' => $this->primaryKey,])
                    ->one();
            if ($modelT) {
                $this->url = $modelT->url;
            }
        }
    }

    /**
     * prepare array of menu items for Bootstrap Nav
     * @param integer $type
     * @param integer $parent
     * @param boolean $childs
     * @return array
     */
    public static function getMenuArray($type, $parent = 0, $childs = false)
    {
        $items = [];
        $childIsActive = false;
        $models = self::find()->where(['active' => self::ACTIVE, 'type' => $type, 'parent' => $parent])->orderBy(['order' => SORT_ASC])->all();
        if (!$models)
            return $items;
        foreach ($models as $model) {
            $i = self::getMenuArray($model->type, $model->primaryKey, true);
            $active = ((yii::$app->request->clearUri == yii::$app->request->clearUrl($model->url)) || (count($i) > 0 && $i['active']));
            if (!$childIsActive)
                $childIsActive = $active;
            $items[] = [
                'label' => self::replaceUserAttributes(((trim($model->title) != '' || $model->icon != '') ? $model->title : $model->url)),
                'options' => ['class' => null], //li
                'submenuOptions' => ((count($i) > 0 && $childIsActive) ? ['class' => 'dropdown-menu'] : ['class' => 'dropdown-menu c']),
                'linkOptions' => (count($i) > 0 ? ['class' => '_'] : []),
                'items' => (count($i) > 0 ? $i['items'] : null),
                'url' => $model->url,
                'linkOptions' => ($model->icon ? ['icon' => $model->icon] : []),
                'active' => $active,
                'visible' => (self::VISIBLE_FOR_ALL_USERS === $model->visible ||
                ($model->visible === self::VISIBLE_FOR_AUTHORIZED_USERS && !yii::$app->user->isGuest) ||
                ($model->visible === self::VISIBLE_FOR_GUESTS && yii::$app->user->isGuest)),
            ];
        }
        return !$childs ? $items : ['items' => $items, 'active' => $childIsActive];
    }

    //@TODO: replace user attributes 
    public static function replaceUserAttributes($text)
    {
        foreach (Helper::user()->identity()->attributes as $k => $v) {
            $text = str_replace('{user.' . $k . '}', $v, $text);
        }
        return $text;
    }

    /**
     * alias link
     * @param array $options
     * @param string $label
     * @return html
     */
    public function getUrlLink($options = [], $label = false)
    {
        $admin = yii::$app->controller->module->id == 'admin';
        if ($admin && count($options) == 0) {
            $options = ['target' => '_blank', 'icon' => 'link'];
        }
        $url = Url::to($this->url, true);
        $options['data'] = [
            'model' => 'Menu',
            'attr' => 'url',
            'id' => $this->primaryKey,
            'href' => $this->url,
            'pjax' => 0,
        ];
        if (!isset($options['title']))
            $options['title'] = $this->title;
        $url = Url::to([$this->url], true);
        return Html::a($label ? $label : ($admin ? $url : $this->title), $url, $options);
    }

}