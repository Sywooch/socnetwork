<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    public $rbacRole;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['rbacRole'], 'string'],
            [['rbacRole', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
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
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->join('LEFT JOIN', '{{%auth_assignment}}', '{{%user.id}}={{%auth_assignment}}.user_id')->andFilterWhere([
            'item_name' => $this->rbacRole
        ]);

        $dataProvider->sort->attributes['rbacRole'] = [
            'asc' => ['{{%auth_assignment}}.item_name' => SORT_ASC],
            'desc' => ['{{%auth_assignment}}.item_name' => SORT_DESC],
        ];

        if ((!$this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            '{{%auth_assignment}}.item_name' => $this->rbacRole,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'auth_key', $this->auth_key])
                ->andFilterWhere(['like', 'password_hash', $this->password_hash])
                ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
                ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }

}