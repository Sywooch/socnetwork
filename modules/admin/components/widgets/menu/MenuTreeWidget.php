<?php

namespace app\modules\admin\components\widgets\menu;

use yii;
use yii\base\Widget;
use app\models\Menu;
use app\components\extend\Html;
use app\components\extend\Url;
use app\models\search\MenuSearch;

class MenuTreeWidget extends Widget
{

    public $type;
    public $model;
    public $view = 'index';

    /**
     * @var array $parents 
     */
    static $parents = [];

    public function run()
    {
        return $this->render($this->view, [
                    'model' => new Menu(),
                    'type' => $this->type,
                    'model' => $this->model
        ]);
    }

    /**
     * 
     * @param type $type
     * @param type $model
     * @return array
     */
    public static function getTreeArray($type, $model = false, $control = false)
    {
        $items[] = static::getRootItem($model);
        $models = Menu::find()->where(['type' => ($type == null ? Menu::TYPE_MAIN : $type)])->orderBy(['parent' => SORT_ASC, 'order' => SORT_ASC])->all();
        if ($models) {
            foreach ($models as $m) {
                extract(static::getMenuManageButtons($m));
                $disabled = static::checkDisabled($m, $model);
                $link = Html::tag('span', $m->url, ['class' => 'a menu-url-link', 'icon' => 'link', 'onclick' => "window.open('" . $m->url . "','_blank');"]);
                $items[] = [
                    'id' => $m->primaryKey,
                    'text' => $m->getIcon(['title' => yii::$app->l->t('icon', ['update' => false])]) . ' ' . ($m->title ? $m->title : ($m->initTranslation(true)) . $m->title)
                    . ($model ? '' : $link . (!$control ? '' : ' - ' . static::warningDisabled($m) . ' &nbsp;&nbsp;' . $view . '&nbsp' . $update . '&nbsp;' . $delete)),
                    'icon' => 'fa fa-file',
                    'parent' => ($m->parent > 0 ? $m->parent : 'root'),
                    'li_attr' => [
                        'data-id' => $m->primaryKey,
                        'data-update-url' => Url::to(['update', 'id' => $m->primaryKey])
                    ],
                    'state' => [
                        'disabled' => $disabled, 'opened' => false, 'selected' => ($model && ($m->primaryKey === $model->parent))]
                ];
            }
        }
        return $items;
    }

    /**
     * 
     * @param type $currentModel
     * @param type $comparedModel
     * @return boolean
     */
    public static function checkDisabled($currentModel, $comparedModel)
    {
        if (in_array($currentModel->parent, static::$parents, true)) {
            static::$parents[] = $currentModel->primaryKey;
            return true;
        }
        if ($comparedModel && ($currentModel->primaryKey === $comparedModel->primaryKey)) {
            static::$parents[] = $currentModel->primaryKey;
            return true;
        }
        return false;
    }

    public static function getRootItem($model)
    {
        return [
            'id' => 'root',
            'text' => yii::$app->l->t('root'),
            'icon' => 'glyphicon glyphicon-folder-open',
            'parent' => '#',
            'state' => [
                'disabled' => !$model,
                'opened' => true,
                'selected' => ($model && ($model->isNewRecord || $model->parent == 0))
            ]
        ];
    }

    public static function getMenuManageButtons($model)
    {

        $onClickUrl = "Common.redirect($(this).data('url'));";

        $updateDefault = yii::$app->user->can('menu-update') ? (Html::ico('pencil', [
                    'onclick' => $onClickUrl,
                    'title' => yii::$app->l->t('view', ['update' => false]),
                    'class' => 'a',
                    'data' => [
                        'type' => 'follow',
                        'url' => Url::to(['update', 'id' => $model->primaryKey])
                    ]
                ])) : '';
        $update = static::checkTButtons($model, $updateDefault);
        $delete = yii::$app->user->can('menu-delete') ? (Html::ico('trash', [
                    'onclick' => "yii.confirm('" . yii::$app->l->t('аre you sure you want to delete this item?') . "',function(){Common.redirect('"
                    . Url::to(['delete', 'id' => $model->primaryKey])
                    . "');});",
                    'title' => yii::$app->l->t('delete', ['update' => false]),
                    'class' => 'a',
                    'data' => [
                        'type' => 'delete',
                        'id' => $model->primaryKey,
                    ]
                ])) : '';
        $view = yii::$app->user->can('menu-view') ? (Html::ico('eye', [
                    'onclick' => $onClickUrl,
                    'title' => yii::$app->l->t('view', ['update' => false]),
                    'class' => 'a',
                    'data' => [
                        'type' => 'follow',
                        'id' => $model->primaryKey,
                        'url' => Url::to(['view', 'id' => $model->primaryKey]),
                    ]
                ])) : '';
        return ['view' => $view, 'delete' => $delete, 'update' => $update];
    }

    public static function checkTButtons($model, $alternative)
    {
        return yii::$app->l->multi ? $model->getTButtons() : $alternative;
    }

    public static function warningDisabled($model)
    {
        return $model->active != Menu::ACTIVE ? '&nbsp;' . Html::ico('exclamation-triangle text-danger', [
                    'title' => yii::$app->l->t('disabled', ['update' => false])
                ]) : '';
    }

}
