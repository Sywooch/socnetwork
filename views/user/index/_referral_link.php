<?php

use app\components\extend\Html;
use app\components\extend\Url;

/* @var $this yii\web\View */
/* @var $user \app\models\User */
/* @var $user app\models\behaviors\user\UserReferralBehavior */
?>

<?php
if (yii::$app->user->id == $user->primaryKey) {
    ?>

    <div class="referal-link">
        <b><?= yii::$app->l->t('referral link') ?>:</b>
        <div class="input-group">
            <input type="text" class="form-control" value="<?= $user->getReferralUrl() ?>" readonly="">
            <span class="input-group-btn">
                <button onclick="yii.mes('<?= yii::$app->l->t('copied') ?>', 'success', 2)" data-clipboard-action="copy" data-clipboard-text="<?= $user->getReferralUrl(); ?>" class="copy-button btn btn-default" type="button" title="" data-original-title="<?= yii::$app->l->t('copy') ?>"><i class="icon">content_copy</i></button>
            </span>
        </div>
    </div>
    <?php
}
?>