<?php

namespace app\modules\admin\components\widgets\rbac\manager;

use yii\web\AssetBundle;

//POS_READY
class rbacManagerAssets extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/components/widgets/rbac/manager/assets';
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