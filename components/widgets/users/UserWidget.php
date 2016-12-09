<?php

namespace app\components\widgets\users;

use yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\components\extend\Html;
use app\components\helper\Helper;
use app\models\UserFriends;
use app\models\search\UserFriendsSearch;
use app\models\User;
use app\models\search\UserSearch;

class UserWidget extends Widget
{

    public $view = 'search';
    public $params = [];
    public $model;

    public function run()
    {
        $isSearchWidget = yii::$app->request->post('userSearchWidget');
        $this->model = new User;
        if ($isSearchWidget) {
            $this->model->load(yii::$app->request->post());
        }
        return $this->render($this->view, [
                    'model' => $this->model,
                    'dataProvider' => $this->getDataProvider()
        ]);
    }

    public function getDataProvider()
    {
        $searchModel = new UserSearch();
        switch ($this->view) {
            case 'search':
                $userPost = yii::$app->request->post('User');
                $isSearchWidget = yii::$app->request->post('userSearchWidget');
                return ($userPost && $isSearchWidget) ? $searchModel->searchPeople($userPost) : null;
        }
    }

}
