<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\File;

/**
 * FileSearch represents the model behind the search form about `app\models\File`.
 * @property    integer    $host
 * @property    string     $path
 * @property    string     $name
 * @property    string     $title
 * @property    string     $extension
 * @property    integer    $size
 * @property    string     $mime
 * @property    integer    $created_at
 * @property    integer    $location
 * @property    boolean    $status
 * @property    string     $url
 */
class FileSearch extends File
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['destination', 'owner', 'host', 'path', 'name', 'title', 'extension', 'size', 'mime', 'created_at', 'status', 'location'], 'safe'],
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
        $query = File::find();
        /* @var $dataProvider ActiveDataProvider */
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort = ['defaultOrder' => ['status' => SORT_ASC]];

        $query->join('LEFT JOIN', '{{%user}}', '{{%user.id}}={{%file.owner}}');
        $query->join('LEFT JOIN', '{{%file_destination}}', '{{%file_destination}}.file_name={{%file.name}}');

        $dataProvider->sort->attributes['owner'] = [
            'asc' => ['{{%user}}.`username`' => SORT_ASC],
            'desc' => ['{{%user}}.`username`' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['destination'] = [
            'asc' => ["GROUP_CONCAT({{%file_destination}}.destination SEPARATOR ',')" => SORT_ASC],
            'desc' => ["GROUP_CONCAT({{%file_destination}}.destination SEPARATOR ',')" => SORT_DESC],
        ];
        $query->groupBy('name');

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'host' => $this->host,
            'path' => $this->path,
            'name' => $this->name,
            'title' => $this->title,
            'extension' => $this->extension,
            'size' => $this->size,
            'mime' => $this->mime,
            'created_at' => $this->created_at,
            'location' => $this->location,
            "{{%file}}.`status`" => $this->status,
        ]);

        $query->andFilterWhere([
            'and',
                ['like', '{{%user.username}}', $this->owner],
                ['like', '{{%file_destination}}.destination', $this->destination],
        ]);

        return $dataProvider;
    }

}
