<?php

namespace app\components\extend\jui;

use yii\jui\Sortable as BaseSortable;

class Sortable extends BaseSortable
{
    public $options = [
        'tag' => 'ul',
        'class' => 'list-group sortable'
    ];
    public $itemOptions = [
        'tag' => 'li',
        'class' => 'text-info list-group-item'
    ];
    public $clientOptions = [
        'cursor' => 'move',
        'connectWith' => '.sortable'
    ];
    public $connected = true;

    public function init()
    {
        parent::init();
    }

}