<?php

namespace app\commands;

use yii\console\Controller;

class CronController extends Controller
{
    public function actionRun()
    {
        \app\models\File::syncTableFiles();
    }

}