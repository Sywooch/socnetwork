<?php

namespace app\modules\admin\components\widgets\rbac\manageUserRoles;

use yii\web\AssetBundle;

class manageUserRolesAssets extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/components/widgets/rbac/manageUserRoles/assets';
    public $js = [
        'js.js',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}