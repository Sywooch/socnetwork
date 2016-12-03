<?php

/**
 * Description of SeoBehavior
 *
 * @author postolachiserghei
 */

namespace app\models\behaviors;

use Yii;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use app\components\extended\Html;
use app\models\Seo;
use app\components\helper\Helper;

class SeoBehavior extends \yii\base\Behavior
{

    public $actionUrl;
    public $seo;
    public $url;
    public $alias;
    public $title;
    public $h1;
    public $keywords;
    public $description;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!$this->owner) {
            $this->seo = new Seo();
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return[
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            BaseActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            BaseActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($event)
    {
        $post = yii::$app->request->post('Seo');
        if ($post) {
            $this->seo->attributes = $post;
            $this->actionUrl = Helper::str()->replaceTagsWithDatatValues($this->actionUrl, $this->owner);
            $this->seo->url = $this->actionUrl;
            if ($this->seo->validate()) {
                $this->seo->save();
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->loadSeo();
    }

    public function loadSeo()
    {
        $this->actionUrl = Helper::str()->replaceTagsWithDatatValues($this->actionUrl, $this->owner);
        $linkUrl = $this->actionUrl;
        $l = yii::$app->request->get('l');
        $seo = Seo::find()->joinWith('seoT')->where('url=:url', ['url' => $linkUrl])->one();
        $this->seo = $seo ? $seo : new Seo ();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        return true;
    }

    /**
     * url
     * @return type
     */
    public function getLinkUrl()
    {
        return Helper::str()->replaceTagsWithDatatValues($this->actionUrl, $this->owner);
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        $this->loadSeo();
        if ($this->seo) {
            $this->seo->delete();
        }
    }

}
