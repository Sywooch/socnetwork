<?php

use app\components\extend\Html;
use app\components\extend\Url;
use app\components\extend\ActiveForm;
use app\components\extend\ListView;

/* @var $dataProvider \yii\data\ActiveDataProvider */
?>

<div class="users-dropdown dropdown <?= $dataProvider ? 'open' : '' ?>">
    <button id="user-list" type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="icon">people</i><span> <?= yii::$app->l->t('people') ?></span>
    </button>
    <div class="auth-menu dropdown-menu" aria-labelledby="user-list">
        <div class="form-group">
            <?php
            $form = ActiveForm::begin([
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => false,
            ]);
            ?>
            <div class="row">
                <div class="col-sm-5">
                    <?=
                    $form->field($model, 'first_name', [
                        'template' => '{input}'
                    ])->textInput(['placeholder' => $model->getAttributeLabel('first_name')])->label('')
                    ?>
                </div>
                <div class="col-sm-5">
                    <?=
                    $form->field($model, 'last_name', [
                        'template' => '{input}'
                    ])->textInput(['placeholder' => $model->getAttributeLabel('last_name')])->label('')
                    ?>
                </div>
                <div class="col-sm-1">
                    <?= Html::submitButton('OK', ['class' => 'btn btn-primary']) ?>
                </div> 
            </div> 
            <?php
            echo $dataProvider ? ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => 'search/_item',
                        'layout' => Html::tag('ul', '{items} {pager}', [
                            'class' => 'list-users'
                        ])
                    ]) : '';
            ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>