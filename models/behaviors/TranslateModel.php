<?php

namespace app\models\behaviors;

use Yii;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use app\models\Languages;
use app\components\extend\Html;
use app\components\extend\extensions\Redactor;
use app\components\extend\Url;

class TranslateModel extends \yii\base\Behavior
{

    public $t;
    public $fk;
    public $l;

    public function init()
    {
        parent::init();
        $l = yii::$app->request->get('l');
        $this->l = $l ? $l : yii::$app->language;
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return[
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave()
    {
        $this->saveTranslation();
        return true;
    }

    /**
     * asign post data to TModel && save
     */
    public function saveTranslation()
    {
        $post = yii::$app->request->post($this->owner->shortClassName);
        if ($post) {
            $this->t->attributes = $post;
            $this->t->{$this->fk} = $this->owner->primaryKey;
            $this->t->language_id = $this->l;
            if ($this->t->validate()) {
                @$this->t->save();
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->initTranslation();
    }

    /**
     * 
     * @param boolean $anyTranslation
     */
    public function initTranslation($anyTranslation = false)
    {
        if ($model = $this->getTranslates($anyTranslation)) {
            foreach ($model->attributes as $k => $v) {
                if (property_exists($this->owner, $k)) {
                    $this->owner->{$k} = $v;
                }
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslates($anyTranslation = false)
    {
        $get = yii::$app->request->get();
        $condition = [$this->fk => $this->owner->primaryKey];
        if (!$anyTranslation)
            $condition['language_id'] = $this->l;
        $this->t->{$this->fk} = $this->owner->primaryKey;
        $model = $this->t->find()->where($condition)->one();
        if ($model)
            $this->t = $model;
        return $model;
    }

    /**
     * @param array $options
     * @return string
     */
    public function getTButtons($options = [])
    {
        if (!yii::$app->l->multi) {
            return '';
        }
        $tmp = '';
        Html::addCssClass($options, 'btn btn-xs ');
        $hasT = 0;
        foreach (yii::$app->l->languages as $k => $v) {
            $o = $options;
            $controller = yii::$app->controller->id;
            $action = yii::$app->controller->action->id;
            $get = ($action == 'index' || $hasT == 0) ? [] : yii::$app->request->get();
            if (!$this->owner->isNewRecord) {
                $hasT = $this->t->find()->where(['AND', [$this->fk => $this->owner->primaryKey, 'language_id' => $k]])->count();
                foreach ($this->owner->primaryKey() as $key) {
                    $get[$key] = $this->owner[$key];
                }
            }
            $hasT > 0 ? Html::addCssClass($o, 'btn-success') : Html::addCssClass($o, 'btn-warning');
            $get[0] = ($this->owner->isNewRecord) ? 'create' : (($action == 'view' && $hasT > 0) ? 'view' : 'update');
            if (!yii::$app->user->can($controller . '-' . $get[0])) {
                continue;
            }
            $get['l'] = $k;
            $url = Url::to($get);
            if ($this->l == $k) {
                Html::addCssClass($o, 'active');
            }
            $ico = ($this->l == $k && ($action == 'create' || $action == 'update' || $action == 'view')) ? 'check' : ($hasT > 0 ? ( $action == 'view' ? 'eye' : 'pencil') : 'plus');
            $o['data'] = ['pjax' => yii::$app->controller->isPjaxAction];
            $o['title'] = $this->getButtonTtitle($ico, $v);
            $o['href'] = $url;
            $o['onclick'] = "Common.redirect('$url')";
            $tmp .= Html::tag('a', strtoupper(($action == 'index' ? $k : $v)) . ' ' . Html::ico($ico), $o);
        }
        return Html::tag('div', $tmp, ['class' => 'btn-group model-t-buttons']);
    }

    /**
     * 
     * @param string $ico
     * @param string $v
     * @return string
     */
    public function getButtonTtitle($ico, $v)
    {
        $title = '';
        switch ($ico) {
            case'eye':
                $title = yii::$app->l->t('view {item}', ['item' => '"' . $v . '"', 'lcf' => true, 'update' => false]);
                break;
            case'pencil':
                $title = yii::$app->l->t('update {item}', [
                    'item' => '"' . $v . '"',
                    'lcf' => true,
                    'update' => false,
                ]);
                break;
            case'plus':
                $title = yii::$app->l->t('add {item}', [
                    'item' => '"' . $v . '"',
                    'lcf' => true,
                    'update' => false,
                ]);
                break;
        }
        return $title;
    }

}
