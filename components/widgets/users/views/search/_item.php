<?php

use app\components\extend\Html;
use app\components\extend\Url;

/* @var $model \app\models\User */
?>

<li>
    <a href="<?= Url::to(['/user/view', 'id' => $model->primaryKey]) ?>">
        <span class="photo photo-50">
            <?= $model->renderAvatar(); ?>
        </span>
        <span>
            <?= $model->fullName; ?>
        </span>
    </a>
    <div class="item-button">        
        <?=
        Html::button('<i class="icon">person_add</i>', [
            'onclick' => 'Friends.inviteToFriends($(this));',
            'data' => [
                'confirm-message' => yii::$app->l->t('invite this user to friends ?'),
                'url' => Url::to(['/friends/invite', 'id' => $model->primaryKey]),
            ]
        ])
        ?>
        <button><i class="icon">mail</i></button>
    </div>
</li>