<?php

use app\components\extend\Html;
use app\components\extend\Url;
use app\models\File;

/* @var $this yii\web\View */
/* @var $model \app\models\User */
/* @var $model \app\models\behaviors\user\UserReferralBehavior */
/* @var $step integger */
/* @var $index integger */
?>


<?php
$colMd = 0;
$colSm = 0;
switch ($step) {
    case 1:
        $colMd = '5 col-md-offset-3';
        $colSm = '5 col-sm-offset-3';
        break;
    case 2:
        $colMd = '4 col-md-offset-1';
        $colSm = '4 col-sm-offset-1';
        break;
    case 3:
        $colMd = '3';
        $colSm = '3';
        break;
    case 4:
        $colMd = '3';
        $colSm = '3';
        break;
    case 5:
        $colMd = '3';
        $colSm = '3';
        break;
}
?>

<?php
if ($step == 4 && ($index == 1)) {
    echo Html::beginTag('div', ['class' => 'col-md-6']);
}
if ($step == 5 && ($index == 1)) {
    echo Html::beginTag('div', ['class' => 'col-md-3']);
}
?>

<a href="<?= Url::to(['/user/view', 'id' => $model->primaryKey]) ?>" class="step-<?= $step; ?> col-sm-<?= $colSm; ?>  col-md-<?= $colMd; ?>">
    <span class="photo">
        <?php
        echo $model->renderAvatar([
            'size' => File::SIZE_ORIGINAL
        ]);
        ?>
    </span>
</a>

<?php
if ($step == 4) {
    if ($index == 4) {
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'col-md-6']);
    }
    if ($remains == 0 || $index == 8) {
        echo Html::endTag('div');
    }
}
if ($step == 5) {
    if ($index == 4 || $index == 8 || $index == 12) {
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'col-md-3']);
    }
    if ($remains == 0 || $index == 16) {
        echo Html::endTag('div');
    }
}
?>