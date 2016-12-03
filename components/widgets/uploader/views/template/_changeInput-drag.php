<div class="jFiler-input-dragDrop <?= $uploader->template ?>">
    <div class="jFiler-input-inner">
        <div class="jFiler-input-icon">
            <i class="icon-jfi-cloud-up-o">
            </i>
        </div>
        <div class="jFiler-input-text">
            <h3>
                <?= yii::$app->l->t('Drag&Drop files here'); ?>
            </h3>
            <span style="display:inline-block; margin: 15px 0"><?= yii::$app->l->t('or', ['lcf' => true]) ?></span>
        </div>
        <a class="jFiler-input-choose-btn btn btn-primary"><?= yii::$app->l->t('Browse Files') ?></a>
    </div>
</div>