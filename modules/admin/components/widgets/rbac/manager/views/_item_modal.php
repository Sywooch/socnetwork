<?php

use yii\bootstrap\Modal;
use app\components\extend\Html;
use yii\bootstrap\Tabs;
use app\components\extend\Url;

Modal::begin([
    'id' => 'rbac-modal-form',
    'header' => Html::tag('h3', yii::$app->l->t('rbac item'), ['class' => 'modal-header-h']),
    'footer' => Html::tag('div', Html::tag('div', yii::$app->l->t('save'), [
                'class' => 'btn btn-success col-md-5 pull-left',
                'onclick' => "sendRbacModalFromData($(this))",
                'data' => [
                    'selector' => '#rbac-form',
                    'url' => Url::to(['index']),
                ],
            ]) . Html::tag('div', yii::$app->l->t('cancel'), [
                'class' => 'btn btn-danger col-md-5 pull-right',
                'onclick' => "$('#rbac-modal-form').modal('hide')"
    ])),
]);
?>
<div class="row">
    <?=
    Html::beginForm(null, null, [
        'id' => 'rbac-form',
    ]);
    ?>
    <?= Html::input('hidden', 'operation', ''); ?>
    <?= Html::input('hidden', 'type', ''); ?>
    <?= Html::input('hidden', 'id', ''); ?>
    <div class="col-md-12">
        <?=
        Html::label(yii::$app->l->t('name') . ' ' . Html::input('text', 'name', '', ['class' => 'form-control']), null, [
            'class' => 'w100'
        ]);
        ?>
    </div>
    <div class="clearfix"></div>
    <br/>
    <?php
    $tabItems = [];
    $i = 0;
    foreach (yii::$app->l->languages as $k => $v):
        ?>
        <?php
        $title = Html::label(yii::$app->l->t('title') . (($k != yii::$app->language && yii::$app->l->multi) ? ' (' . $v . ')' : '') . Html::input('text', 'data[t][' . $k . '][title]', '', ['class' => 'form-control']), null, [
                    'class' => 'w100'
        ]);
        $desc = Html::label(yii::$app->l->t('description') . (($k != yii::$app->language && yii::$app->l->multi) ? ' (' . $v . ')' : '') . Html::textarea('data[t][' . $k . '][description]', '', ['class' => 'form-control']), null, [
                    'class' => 'w100'
        ]);
        $content = '<br/>' . Html::tag('div', $title);
        $content .= Html::tag('div', $desc);
        $tabItems[] = [
            'label' => yii::$app->l->multi ? $v : yii::$app->l->t('info'),
            'content' => $content,
            'active' => ($k == yii::$app->language)
        ];
        $i++;
        ?>
    <?php endforeach; ?>

    <div class="col-md-12">
        <?= count($tabItems) > 0 ? Tabs::widget(['items' => $tabItems]) : ''; ?>
    </div>

    <?= Html::endForm(); ?>
</div>
<div class="clearfix"></div>

<?php
Modal::end();
