<?php

use app\components\extend\Html;
use app\components\extend\Url;
use app\components\helper\Helper;
use app\components\widgets\users\UserWidget;

$user = Helper::user()->identity();

/* @var $user app\models\User */
?>
<div class="sidebar-open">
    <i class="icon">menu</i>
</div>

<?= UserWidget::widget(); ?>

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