<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\ListView;

/* @var $this yii\web\View */
/* @var $model \app\models\User */
/* @var $model \app\models\behaviors\user\UserReferralBehavior */
/* @var $team \yii\db\ActiveQuery */

$countTeamMemBers = $team->count();
$this->title = yii::$app->l->t('my team ({nr})', [
    'update' => false,
    'nr' => $countTeamMemBers
        ]);
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHeader'] = $this->title;
?>


<div class="row team-list">
    <?php
    $step = 1;
    $steps = [
        1 => 1,
        2 => 2,
        3 => 4,
        4 => 8,
        5 => 16,
    ];
    $items = [];
    $index = 1;
    $teamUsers = $team->limit(30)->orderBy(["countReferrals" => SORT_DESC])->all();
    $teamUsers = array_merge([$model],$teamUsers);
    foreach ($teamUsers as $u) {
        if ($step > 5) {
            break;
        }
        $items[$step][] = $u->primaryKey;


        echo $this->render('team/_item', [
            'model' => $u,
            'index' => $index,
            'remains' => ($countTeamMemBers - $index),
            'step' => ($step)
        ]);

        if (count($items[$step]) == $steps[$step]) {
            $index = 0;
            $step++;
        }
        $index++;
    }
    ?>
</div>