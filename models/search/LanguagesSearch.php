<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Languages;

/**
 * LanguagesSearch represents the model behind the search form about `app\models\Languages`.
 */
class LanguagesSearch extends Languages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'language_name'], 'safe'],
            [['language_active', 'language_is_default'], 'integer'],
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
        $query = Languages::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'language_active' => $this->language_active,
            'language_is_default' => $this->language_is_default,
        ]);

        $query->andFilterWhere(['like', 'language_id', $this->language_id])
                ->andFilterWhere(['like', 'language_name', $this->language_name]);

        return $dataProvider;
    }

}