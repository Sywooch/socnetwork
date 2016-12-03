<?php

use app\components\extend\Html;
use app\components\extend\Url;
use app\components\helper\Helper;

$user = Helper::user()->identity();

/* @var $user app\models\User */
?>
<div class="sidebar-open">
    <i class="icon">menu</i>
</div>
<div class="users-dropdown dropdown">
    <button id="user-list" type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="icon">people</i><span> Люди</span>
    </button>
    <div class="auth-menu dropdown-menu" aria-labelledby="user-list">
        <form class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-5">
                    <input type="text" class="form-control" placeholder="Имя">
                </div>
                <div class="col-sm-5">
                    <input type="text" class="form-control" placeholder="Фамилия">
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-primary" type="button"><i class="icon">search</i></button>
                </div>
            </div>
            <ul class="list-users">
                <li>
                    <a href="#">
                        <span class="photo photo-50"><img src="/public/bps/img/user.jpg" alt=""></span>
                        <span>Аннита Моржова</span>
                    </a>
                    <div class="item-button">
                        <button><i class="icon">person_add</i></button>
                        <button><i class="icon">mail</i></button>
                    </div>
                </li>
                <li>
                    <a href="#">
                        <span class="photo photo-50"><img src="/public/bps/img/user.jpg" alt=""></span>
                        <span>Аннита Моржова</span>
                    </a>
                    <div class="item-button">
                        <button><i class="icon">person_add</i></button>
                        <button><i class="icon">mail</i></button>
                    </div>
                </li>
                <li>
                    <a href="#">
                        <span class="photo photo-50"><img src="/public/bps/img/user.jpg" alt=""></span>
                        <span>Аннита Моржова</span>
                    </a>
                    <div class="item-button">
                        <button><i class="icon">person_add</i></button>
                        <button><i class="icon">mail</i></button>
                    </div>
                </li>
            </ul>
        </form>
    </div>
</div>
<div class="profile-menu">
    <?php
    if (!yii::$app->user->isGuest) {
        echo Html::a(Html::tag('span', $user->fullName, [
                    'class' => 'name'
                ]) . Html::tag('span', $user->renderAvatar(), [
                    'class' => 'photo photo-40'
                ]), Url::to(['/user/index']));

        echo Html::a(yii::$app->l->t('quit'), Url::to(['/site/logout']), [
            'class' => 'logout-link'
        ]);
    }
    ?>
</div>