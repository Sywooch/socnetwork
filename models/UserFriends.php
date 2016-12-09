<?php

namespace app\models;

use yii;

/**
 * This is the model class for table "{{%user_friends}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $sender_id
 * @property integer $status
 */
class UserFriends extends \app\components\extend\Model
{

    const STATUS_REQUEST = 0;
    const STATUS_IS_FRIEND = 1;
    const STATUS_REJECTED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_friends}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['user_id', 'sender_id', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => yii::$app->l->t('user'),
            'sender_id' => yii::$app->l->t('friend'),
            'status' => yii::$app->l->t('status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return User::find()->where(['id' => $this->user_id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return User::find()->where(['id' => $this->sender_id]);
    }

    public static function primaryKey()
    {
        return ['user_id', 'sender_id'];
    }

    public function getFriendOf($userId)
    {
        if ((int) $userId == (int) $this->sender_id) {
            $u = $this->getUser()->one();
        } else {
            $u = $this->getSender()->one();
        }
        return $u;
    }

}
