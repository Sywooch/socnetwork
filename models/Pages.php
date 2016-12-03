<?php

namespace app\models;

use Yii;
use app\models\t\PagesT;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property integer $id
 * @property integer $status
 */
class Pages extends \app\components\extend\Model
{
    public $title;
    public $content;

    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;

    /**
     * @param integer $status
     * @param boolean $withLiveEdit (return translated labels wrapped in html tag if TRUE)
     * @return array/string
     */
    public function getStatusLabels($status = false, $withLiveEdit = true)
    {
        $ar = [
            self::STATUS_ACTIVE => yii::$app->l->t('item is active', ['update' => $withLiveEdit]),
            self::STATUS_NOT_ACTIVE => yii::$app->l->t('item is not active', ['update' => $withLiveEdit]),
        ];
        return $status === false ? $ar : $ar[$status];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors() + [
            't' => [
                'class' => behaviors\TranslateModel::className(),
                't' => new PagesT(),
                'fk' => 'page_id',
            ],
            'seo' => [
                'class' => behaviors\SeoBehavior::className(),
                'seo' => new Seo(),
                'actionUrl' => '/pages/view?id={id}'
            ],
            'search' => [
                'class' => behaviors\SearchBehavior::className(),
                'writeSearchDataIf' => ['status' => self::STATUS_ACTIVE],
                'searchedAttributes' => [
                    'title', 'content'
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge([
            [['status'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE]
                ], (new PagesT())->rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $pagesT = new PagesT();
        return [
            'title' => $pagesT->getAttributeLabel('title'),
            'content' => $pagesT->getAttributeLabel('content'),
            'status' => yii::$app->l->t('status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagesT()
    {
        return $this->hasMany(PagesT::className(), ['page_id' => 'id']);
    }

}