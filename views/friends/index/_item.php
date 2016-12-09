<?php

use app\components\extend\Html;
use app\models\UserFriends;
use app\components\extend\Url;
use app\models\File;

/* @var $model \app\models\UserFriends */
$user = $model->user;
?>
<li>
    <div class="row">
        <div class="col-md-2 col-sm-2 col-xs-3">
            <a href="#">
                <span class="photo">
                    <?=
                    $user->renderAvatar([
                        'size' => File::SIZE_ORIGINAL
                    ]);
                    ?>
                </span>
            </a>
        </div>
        <div class="col-md-8 col-sm-7 col-xs-7">
            <?= Html::a($user->fullName, Url::to(['/user/view', 'id' => $user->id])) ?>
            <span><?= yii::$app->l->t('circle 1') ?></span>
            <?=
            Html::a(yii::$app->l->t('write a message'), '#', [
                'class' => 'send-message',
            ])
            ?>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-2">
            <?php
            if ($model->status == UserFriends::STATUS_IS_FRIEND) {
                echo Html::a('<span>' . yii::$app->l->t('delete friend') . '</span><i class="icon">delete</i>', '#', [
                    'onclick' => 'Friends.delete($(this));return false;',
                    'class' => 'remove',
                    'data' => [
                        'url' => Url::to(['delete', 'id' => $model->sender_id]),
                        'confirm-message' => yii::$app->l->t('delete friend'),
                    ]
                ]);
            } else {
                $userIsReceiver = ($model->sender_id != yii::$app->user->id);
                echo $userIsReceiver ? Html::a('<span>' . yii::$app->l->t('accept friend') . '</span><i class="icon">delete</i>', '#', [
                            'onclick' => 'Friends.accept($(this));return false;',
                            'class' => 'add',
                            'data' => [
                                'url' => Url::to(['accept', 'id' => $model->sender_id]),
                                'confirm-message' => yii::$app->l->t('accept friend'),
                            ]
                        ]) : '';
                echo Html::a('<span>' . yii::$app->l->t(($userIsReceiver ? 'reject friend' : 'cancel request')) . '</span><i class="icon">delete</i>', '#', [
                    'onclick' => 'Friends.reject($(this));return false;',
                    'class' => 'remove',
                    'data' => [
                        'url' => Url::to([($userIsReceiver ? 'reject' : 'cancel'), 'id' => ($userIsReceiver ? $model->sender_id : $model->user_id)]),
                        'confirm-message' => yii::$app->l->t('reject friend'),
                    ]
                ]);
            }
            ?>
        </div>
    </div>
</li>