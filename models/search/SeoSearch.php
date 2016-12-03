<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Seo;

/**
 * SeoSearch represents the model behind the search form about `app\models\Seo`.
 */
class SeoSearch extends Seo
{
//    public $title;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['url'], 'safe'],
            [['alias', 'h1', 'keywords', 'description'], 'string'],
            [['title'], 'string', 'max' => 100],
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
        $query = Seo::find();
        $query->joinWith('seoT');
        $query->groupBy('id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        $dataProvider->sort->attributes['title'] = [
            'asc' => ['title' => SORT_ASC],
            'desc' => ['title' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['h1'] = [
            'asc' => ['h1' => SORT_ASC],
            'desc' => ['h1' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['alias'] = [
            'asc' => ['h1' => SORT_ASC],
            'desc' => ['h1' => SORT_DESC],
        ];


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
        ]);


        $query->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['like', 'h1', $this->h1]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'alias', $this->alias]);

        return $dataProvider;
    }

}