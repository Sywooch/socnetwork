<?php

namespace app\components\extend;

use Yii;
use yii\grid\DataColumn as BaseDataColumn;

class DataColumn extends BaseDataColumn
{
    public $format = 'raw';

    public function init()
    {
        parent::init();
        $this->encodeLabel = !yii::$app->l->liveEditT;
    }

}