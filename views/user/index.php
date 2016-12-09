<?php

use app\components\extend\Html;
use app\components\extend\Nav;
use app\components\extend\ListView;
use app\components\extend\Url;

/* @var $this yii\web\View */
/* @var $user \app\models\User */


$this->title = yii::$app->l->t('my page', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('User Friends');
?>


<div class="row">
    <div class="col-md-3 col-sm-5 col-">
        <div class="avatar">
            <?=
            $user->renderAvatar([
                'size' => app\models\File::SIZE_MD
            ]);
            ?>
        </div>
        <?php
        if ($user->primaryKey != yii::$app->user->id) {
            echo Html::a('<i class="icon">email</i> ' . yii::$app->l->t('message'), '#', [
                'class' => 'edit-avatar',
                'onclick' => "yii.mes('/* TODO #PS: need to be done */')",
            ]);
            if (!yii::$app->user->identity->getIsFriendToMe($user->id)) {
                echo Html::a('<i class="icon">person_add</i> ' . yii::$app->l->t('invite to friends'), '#', [
                    'class' => 'add-friend',
                    'onclick' => 'Friends.inviteToFriends($(this));',
                    'data' => [
                        'confirm-message' => yii::$app->l->t('invite this user to friends ?'),
                        'url' => Url::to(['/friends/invite', 'id' => $user->primaryKey]),
                    ]
                ]);
            }
        }
        ?>
        <div class="profile-block">
            <div class="head">
                <?= yii::$app->l->t('friends') ?> - <?= $user->getFriends()->count(); ?> <?= Html::a(yii::$app->l->t('all friends'), Url::to(['/friends'])) ?>
            </div>
            <div class="row">
                <?php
                foreach ($user->getFriends()->all() as $friend) {
                    $fuser = $friend->sender_id != $user->primaryKey ? $friend->getSender()->one() : $friend->getUser()->one();
                    $favatar = '<span class="photo">' . $fuser->renderAvatar() . '</span>';
                    echo Html::tag('div', Html::a($favatar . $fuser->fullName, Url::to(['/user/view', 'id' => $fuser->primaryKey])), [
                        'class' => 'col-md-6 col-sm-6 col-xs-4',
                    ]);
                }
                ?>

            </div>
        </div>
    </div>
    <div class="col-md-9 col-sm-7">
        <div class="title"><?= $user->fullName; ?> <span class="status online">Online</span></div>
        <ul class="profile-list">
            <li>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <?= $user->getAttributeLabel('country'); ?>
                    </div>
                    <div class="col-md-9 col-sm-9">
                        <?= $user->country; ?>
                        <span onclick="yii.mes('/* TODO #PS: need to be done */')"class="subscribe off">Подписка <i class="icon">remove</i></span>
                    </div>
                </div>
            </li>
            <li>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <?= $user->getAttributeLabel('city'); ?>
                    </div>
                    <div class="col-md-9 col-sm-9">
                        <?= $user->city ?>
                    </div>
                </div>
            </li>
            <li class="title"><?= yii::$app->l->t('contacts'); ?></li>
            <li>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <?= $user->getAttributeLabel('skype'); ?>
                    </div>
                    <div class="col-md-9 col-sm-9">
                        <?= $user->skype ?>
                    </div>
                </div>
            </li>
            <li class="title"><?= yii::$app->l->t('personal') ?></li>
            <li>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <?= $user->getAttributeLabel('gender'); ?>
                    </div>
                    <div class="col-md-9 col-sm-9">
                        <?= $user->gender ?>
                    </div>
                </div>
            </li>
            <li>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <?= $user->getAttributeLabel('about'); ?>
                    </div>
                    <div class="col-md-9 col-sm-9">
                        <?= $user->about ?>
                    </div>
                </div>
            </li>
        </ul>
        <h3>Стена пользователя <b><?= $user->fullName; ?></b></h3>
        <div class="profile-wall">
            <form action="">
                <textarea class="form-control" rows="5" placeholder="Введите текст"></textarea>
                <button class="btn btn-primary" type="submit">Написать</button>
            </form>
            <small>Всего записей - 2</small>
            <ul>
                <li>
                    <div class="row">
                        <div class="col-md-2 col-sm-3 col-xs-3">
                            <a href="#"><span class="photo"><img src="/img/user.jpg" alt=""></span></a>
                        </div>
                        <div class="col-md-10 col-sm-9 col-xs-9">
                            <div class="name">
                                <a href="#">Аннита моржова</a> <span>Сегодня в 18:43</span>
                            </div>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur rem commodi quibusdam mollitia, dolores laudantium, cumque nesciunt, atque deserunt earum recusandae praesentium consequuntur error illo non facilis. Quod magni, aut.
                            </p>
                            <div class="links">
                                <a href="#"><i class="icon">delete</i> <span>Удалить</span></a> <a href="#"><i class="icon">reply</i> <span>Ответить</span></a>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="col-md-2 col-sm-3 col-xs-3">
                            <a href="#"><span class="photo"><img src="/img/user.jpg" alt=""></span></a>
                        </div>
                        <div class="col-md-10 col-sm-9 col-xs-9">
                            <div class="name">
                                <a href="#">Аннита моржова</a> <span>Сегодня в 18:43</span>
                            </div>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur rem commodi quibusdam mollitia, dolores laudantium, cumque nesciunt, atque deserunt earum recusandae praesentium consequuntur error illo non facilis. Quod magni, aut.
                            </p>
                            <div class="links">
                                <a href="#"><i class="icon">delete</i> <span>Удалить</span></a> <a href="#"><i class="icon">reply</i> <span>Ответить</span></a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>