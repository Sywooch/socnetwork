<?php

namespace app\components\extend;

use Yii;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    public function init()
    {
        parent::init();
        $this->encodeLabels = !yii::$app->l->liveEditT;
    }

}