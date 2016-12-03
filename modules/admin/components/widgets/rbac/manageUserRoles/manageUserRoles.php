<?php

namespace app\modules\admin\components\widgets\rbac\manageUserRoles;

use \yii\base\Widget;
use \app\modules\admin\components\rbac\rbac;
use \yii\helpers\Html;
use \yii\rbac\Item;

class manageUserRoles extends Widget
{
    public function run()
    {
        $rbac = new rbac;
        $rbac->getRoles();

        return $this->render('index', [
            'rbac' => $rbac,
            'type' => Item::TYPE_ROLE
        ]);
    }

}