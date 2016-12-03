<?php

namespace app\components\helper;

use Yii;

class UserHelper
{
    public function identity()
    {
        $model = new \app\models\User();
        if (!yii::$app->user->isGuest)
            $model->attributes = Yii::$app->user->identity->attributes;
        return $model;
    }

}