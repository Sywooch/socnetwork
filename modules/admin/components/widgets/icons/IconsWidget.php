<?php

namespace app\modules\admin\components\widgets\icons;

use yii;
use yii\base\Widget;

class IconsWidget extends Widget
{
    public $icon;
    public $data = [];
    public $view = 'index';

    public function run()
    {
        return $this->render($this->view, [
                    'icon' => $this->icon,
                    'data' => $this->data,
        ]);
    }

}