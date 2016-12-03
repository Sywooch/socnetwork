<?php
/**
 * Description of SearchBehavior
 *
 * @author postolachiserghei
 */

namespace app\models\behaviors;

use Yii;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use app\models\Search;
use app\models\SearchValues;
use app\components\extend\Html;
use app\components\extend\Url;

class SearchBehavior extends \yii\base\Behavior
{
    public $s;
    public $searchedAttributes;
    public $searchParams = [];
    public $writeSearchDataIf = true;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return[
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($event)
    {
        $model = Search::find()->where(['link' => $this->owner->actionUrl, 'language_id' => $this->owner->t->language_id])->one();
        if (!$model) {
            if (!$this->searchDataShouldBeInserted()) {
                return true;
            }
            $model = new Search ();
        } else {
            if (!$this->searchDataShouldBeInserted()) {
                SearchValues::deleteAll(['search_id' => $model->primaryKey]);
                $model->delete();
                return true;
            }
        }
        $model->link = $this->owner->actionUrl;
        $model->model = $this->owner->shortClassName;
        $model->language_id = $this->owner->t->language_id;
        $model->params = $this->getDefaultParams();
        if ($model->save()) {
            SearchValues::deleteAll(['search_id' => $model->primaryKey]);
            foreach ($this->searchedAttributes as $k => $v) {
                $value = new SearchValues();
                $value->search_id = $model->primaryKey;
                $value->attribute = $v;
                $value->value = strip_tags($this->owner->{$v});
                $value->save();
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete($event)
    {
        if ($model = Search::find()->where(['link' => $this->owner->actionUrl, 'language_id' => $this->owner->t->language_id])->one()) {
            SearchValues::deleteAll(['search_id' => $model->primaryKey]);
            $model->delete();
        }
        return true;
    }

    /**
     * get params of search record
     * @return type
     */
    public function getDefaultParams()
    {
        $layout = '';
        $i = 0;
        foreach ($this->searchedAttributes as $k => $v) {
            $attr = '{' . $v . '}';
            $layout.= ($i == 0 ? Html::tag('h4', $attr) : Html::tag('p', $attr));
            $i++;
        }
        $layout.= Html::a(yii::$app->l->t('more'), '{itemUrl}', ['class' => 'btn btn-link pull-right']);
        $layout.= Html::tag('div', '', ['class' => 'clearfix']);
        $params = array_merge([
            'className' => $this->owner->className(),
            'primaryKey' => $this->owner->primaryKey,
            'layout' => Html::tag('div', $layout, ['class' => 'search-result-item thumbnail']),
                ], $this->searchParams);
        return serialize($params);
    }

    /**
     * check if data should be saved for searchF
     * @return boolean
     */
    public function searchDataShouldBeInserted()
    {
        if ($this->writeSearchDataIf && is_array($this->writeSearchDataIf)) {
            foreach ($this->writeSearchDataIf as $k => $v) {
                if ($this->owner{$k} != $v) {
                    return false;
                }
            }
        }
        return $this->writeSearchDataIf;
    }

}