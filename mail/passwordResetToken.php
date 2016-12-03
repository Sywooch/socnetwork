<?php

use app\components\extend\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$link = Yii::$app->urlManager->createAbsoluteUrl([$resetLink, 'token' => $user->password_reset_token]);
$this->title = $subject;
?>


<?=
Html::tag('span', yii::$app->l->t('hello {username}', [
            'username' => Html::encode($user->username)
]));
?>

<?=
Html::tag('p', yii::$app->l->t('Follow the link below to reset your password: {link}', [
            'link' => Html::tag('div', Html::a(Html::encode($link), $link))
]));
?>