<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pages;
use \yii\db\Query;
use \app\models\Seo;
use \app\models\t\SeoT;
use \app\models\t\PagesT;
use app\components\helper\Helper;

/**
 * PagesSearch represents the model behind the search form about `app\models\Pages`.
 */
class PagesSearch extends Pages
{
    public $title;
    public $alias;
    public $h1;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['title', 'alias', 'h1'], 'string'],
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
        $seoTransTab = SeoT::tableName();
        $seoTab = Seo::tableName();
        $pgTab = Pages::tableName();
        $pgTransTab = PagesT::tableName();


        $query = Pages::find();
        $query->select($pgTab . '.*,'
                . $pgTransTab . '.*,'
                . $seoTab . '.url,'
        );
        $query->joinWith(['pagesT']);
        $query->join('LEFT JOIN', [$seoTab], $seoTab . '.url=concat("' . Helper::str()->replaceTagsWithDatatValues($this->actionUrl, $this) . '",' . $pgTab . '.id)');
        $query->join('LEFT JOIN', [$seoTransTab], $seoTransTab . '.seo_id=' . $seoTab . '.id');
        $query->groupBy([$pgTab . '.id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        $dataProvider->sort->attributes['title'] = [
            'asc' => [$pgTransTab . '.title' => SORT_ASC],
            'desc' => [$pgTransTab . '.title' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['h1'] = [
            'asc' => [$seoTransTab . '.h1' => SORT_ASC],
            'desc' => [$seoTransTab . '.h1' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['alias'] = [
            'asc' => [$seoTransTab . '.alias' => SORT_ASC],
            'desc' => [$seoTransTab . '.alias' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            $pgTab . '.status' => $this->status,
        ]);
        $query->andFilterWhere([
            'and',
            ['like', $seoTransTab . '.alias', $this->alias],
            ['like', $pgTransTab . '.title', $this->title],
            ['like', $seoTransTab . '.h1', $this->h1,]
        ]);

        return $dataProvider;
    }

}