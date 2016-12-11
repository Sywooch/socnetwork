
<?php

use app\components\extend\Html;
use app\components\extend\Url;
use app\models\File;

/* @var $this yii\web\View */
/* @var $user \app\models\User */
?>

<div class="profile-block">
    <div class="head">
        <?= yii::$app->l->t('friends') ?> - <?= $user->getFriends()->count(); ?> <?= Html::a(yii::$app->l->t('all friends'), Url::to(['/friends', 'id' => $user->primaryKey])) ?>
    </div>
    <div class="row">
        <?php
        $friends = $user->getFriends()->all();
        if (count($friends) > 0) {
            foreach ($friends as $friend) {
                /* @var $friend \app\models\UserFriends */
                $fuser = $friend->getFriendOf($user->id);
                $favatar = '<span class="photo">' . $fuser->renderAvatar([
                            'size' => File::SIZE_ORIGINAL
                        ]) . '</span>';
                echo Html::tag('div', Html::a($favatar . $fuser->fullName, Url::to(['/user/view', 'id' => $fuser->primaryKey])), [
                    'class' => 'col-md-6 col-sm-6 col-xs-4',
                ]);
            }
        }
        ?>

    </div>
</div>