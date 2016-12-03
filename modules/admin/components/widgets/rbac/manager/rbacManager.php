<?php

namespace app\modules\admin\components\widgets\rbac\manager;

use yii\base\Widget;
use app\modules\admin\components\rbac\rbac;
use yii\helpers\Html;
use \yii\rbac\Item;

class rbacManager extends Widget
{
    public $type;

    public function run()
    {
        $rbac = new rbac;
        $rbac->getRoles();
        $rbac->getPermissions();

        $manager = $this->render('index', [
            'rbac' => $rbac,
            'type' => $this->type
        ]);
        return $manager . ($this->type == Item::TYPE_PERMISSION ? $this->render('_item_modal') : '');
    }

}