<?php

use app\components\extend\Html;
use app\components\helper\Helper;
use app\components\extend\Url;

$foundModel = new $model->params->className;

if ($m = $foundModel::findOne($model->params->primaryKey)) {
    echo Helper::str()->replaceTagsWithDatatValues($model->params->layout, ((array) $m + [
        'itemUrl' => Url::to($m->actionUrl)
    ]));
}