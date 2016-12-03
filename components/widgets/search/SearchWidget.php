<?php

namespace app\components\widgets\search;

use yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\models\Search;
use app\models\SearchValues;
use app\components\extend\Html;
use app\components\helper\Helper;

class SearchWidget extends Widget
{
    public $view = 'index';

    public function run()
    {
        $request = yii::$app->request->get('Search');
        if ($request) {
            $query = Search::find();
            $query->select(Search::tableName() . '.*,MATCH (value) AGAINST ("' . $request . '") as score');
            $query->joinWith('values');
            $query->where('MATCH (value) AGAINST ("' . $request . '" IN BOOLEAN MODE)');
            $query->orderBy(['score' => SORT_DESC]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query
            ]);
            yii::$app->view->params['pageHeader'] = Html::tag('h1', Helper::data()->getParam('h1', yii::$app->l->t('search')) . ' "' . $request . '"');
        }
        return $this->render($this->view, [
                    'post' => $request,
                    'dataProvider' => isset($dataProvider) ? $dataProvider : null
        ]);
    }

}