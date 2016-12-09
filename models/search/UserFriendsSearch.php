<?php

namespace app\models\search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserFriends;

/**
 * UserFriendsSearch represents the model behind the search form about `app\models\UserFriends`.
 */
class UserFriendsSearch extends UserFriends
{

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
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchFriends($params = [])
    {
        $query = UserFriends::find();
        $query->where('(user_id=:uid OR sender_id=:uid) AND status=:stat', [
            'uid' => $this->user_id,
            'stat' => UserFriends::STATUS_IS_FRIEND,
        ]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchRequests($params = [])
    {
        $query = UserFriends::find();
        $query->where('(sender_id=:uid OR user_id=:uid) AND status=:stat', [
            'uid' => $this->user_id,
            'stat' => UserFriends::STATUS_REQUEST
        ]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

}
