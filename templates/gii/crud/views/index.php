<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>
use app\components\extend\Html;
use app\components\extend\Nav;

use <?= $generator->indexWidgetType === 'grid' ? "app\\components\\extend\\GridView" : "app\\components\\extend\\ListView" ?>;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = yii::$app->l->t('Manage <?= Inflector::camel2words(StringHelper::basename($generator->modelClass))?>', ['update' => false]);
$this->params['breadcrumbs'][] = yii::$app->l->t('<?= Inflector::camel2words(StringHelper::basename($generator->modelClass))?>');
$this->params['pageHeader'] = Html::tag('h1', yii::$app->l->t('Manage <?= Inflector::camel2words(StringHelper::basename($generator->modelClass))?>'));
$this->params['menu'] = Nav::CrudActions($model);

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index"> 
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>
 

<?php if ($generator->indexWidgetType === 'grid'): ?>
    
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
             ['class' => 'yii\grid\SerialColumn'],
            GridView::checkboxColumn(),

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>

            [
                'class' => 'app\components\extend\ActionColumn',
                'template' => Html::tag('div', '{view} {update} {delete}', ['class' => 'grid-view-actions']),
                'buttons' => GridView::DefaultActions($model),
            ],
        ],
    ]); ?>

<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>

</div>
