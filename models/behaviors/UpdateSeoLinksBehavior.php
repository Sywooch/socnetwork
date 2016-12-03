<?php
/**
 * Description of UpdateSeoLinksBehavior
 *
 * @author postolachiserghei
 */

namespace app\models\behaviors;

use yii;
use yii\db\BaseActiveRecord;

class UpdateSeoLinksBehavior extends \yii\base\Behavior
{
    public $modelsTobeUpdated = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->modelsTobeUpdated = [
            'menu' => [
                'class' => \app\models\t\MenuT::className(),
                'attribute' => 'url',
            ]
        ];
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return[
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeUpdate',
            BaseActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeUpdate($event)
    {
        foreach ($this->modelsTobeUpdated as $model) {
            if (array_key_exists('class', $model) && array_key_exists('attribute', $model)) {
                $m = $model['class'];
                $item = (new $m())->find()->where($model['attribute'] . '=:alias OR ' . $model['attribute'] . '=:url', [
                            'alias' => !$this->owner->isNewRecord ? $this->owner->oldAttributes['alias'] : $this->owner->attributes['alias'],
                            'url' => $this->owner->seo->url,
                        ])->one();
                if ($item) {
                    $item->{$model['attribute']} = $this->owner->attributes['alias'];
                    $item->save();
                }
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function beforeDelete($event)
    {
        foreach ($this->modelsTobeUpdated as $model) {
            if (array_key_exists('class', $model) && array_key_exists('attribute', $model)) {
                $m = $model['class'];
                $item = (new $m())->find()->where($model['attribute'] . '=:alias', [
                            'alias' => $this->owner->attributes['alias'],
                        ])->one();
                if ($item) {
                    $item->{$model['attribute']} = $this->owner->seo->url;
                    $item->save();
                }
            }
        }
        return true;
    }

}