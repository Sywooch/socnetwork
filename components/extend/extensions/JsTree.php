<?php

namespace app\components\extend\extensions;

use \yiidreamteam\jstree\JsTree as JsTreeW;

class JsTree extends JsTreeW
{
    public $jsOptions = [];
    public $containerTag = 'div';
    public $containerOptions = [];
    public $bundledTheme = 'default';

    public function init()
    {
        parent::init();
    }

}