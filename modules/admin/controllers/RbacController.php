<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\components\AdminController;
use app\components\extend\Html;
use app\modules\admin\components\rbac\rbac;
use yii\helpers\Json;
use app\modules\admin\components\rbac\ItemHelper;
use yii\rbac\Item;
use app\components\helper\Helper;

class RbacController extends AdminController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    public function actionIndex()
    {
        $this->deleteItem();
        $this->addRemoveChildToItem();
        $this->manageItems();
        return $this->render('index');
    }

    public function addRemoveChildToItem()
    {
        if (yii::$app->request->isAjax) {
            $post = yii::$app->request->post();
            $rightData = isset($post['child'], $post['item'], $post['operation'], $post['type']);
            if ($rightData && $post['operation'] == 'addRemoveChildToItem') {
                $ar = [];
                $rbac = new rbac();
                $item = new ItemHelper();
                $item->setAttributes($post);
                if ($post['type'] == 'remove') {
                    $result = $item->removeChild((new ItemHelper(['name' => $post['item']])), (new ItemHelper(['name' => $post['child']])));
                } else {
                    $result = $item->addChild((new ItemHelper(['name' => $post['item']])), (new ItemHelper(['name' => $post['child']])));
                }
                $ar['response'] = $result ? 'success' : 'error';
                $ar['message'] = Helper::str()->getDefaultMessage($ar['response']);
                die(Json::encode($ar));
            }
        }
    }

    public function deleteItem()
    {
        if (yii::$app->request->isAjax) {
            $ar = [];
            $post = yii::$app->request->post();
            $rightData = isset($post['name'], $post['type'], $post['operation']);
            if ($rightData && $post['operation'] == 'deleteItem') {
                $rbac = new rbac();
                $item = new ItemHelper();
                $item->setAttributes($post);
                $result = $item->remove($post);
                $ar['response'] = $result ? 'success' : 'error';
                $ar['message'] = Helper::str()->getDefaultMessage($ar['response']);
                ;
                die(Json::encode($ar));
            }
        }
    }

    public function manageItems()
    {
        $post = yii::$app->request->post();
        if ($post && array_key_exists('data', $post))
            parse_str($post['data'], $p);
        $rightData = isset($p, $p['data'], $p['id'], $p['name'], $p['type'], $p['operation'], $post['type']);
        if (yii::$app->request->isAjax && $rightData) {
            $ar = [];
            if ($rightData && $post['type'] == 'sendRbacModalFromData') {
                $item = new ItemHelper();
                $item->setAttributes($p);
                $result = $p['operation'] != 'add' ? $item->update($p['id'], $p['data']) : $item->add($p['data']);
                $ar['result'] = $result ? $this->getItemView($p['name'], $p['type'], $p['operation']) : '';
                $ar['response'] = $result ? 'success' : 'error';
                $ar['type'] = $p['type'];
                $ar['name'] = $p['id'];
                $ar['message'] = Helper::str()->getDefaultMessage($ar['response']);
                die(Json::encode($ar));
            } else {
                $ar['response'] = 'error';
                $ar['message'] = Helper::str()->getDefaultMessage($ar['response']);
                die(Json::encode($ar));
            }
        }
    }

    public function getItemView($name, $type, $operation)
    {
        $rbac = new rbac();
        $type == Item::TYPE_PERMISSION ? $rbac->getPermission($name) : $rbac->getRole($name);
        if (array_key_exists($type, $rbac->items)) {
            return $this->renderPartial('@app/modules/admin/components/widgets/rbac/manager/views/_item', [
                        'rbac' => $rbac,
                        'item' => $rbac->items[$type][0],
                        'noContainer' => ($operation != 'add')
            ]);
        }
    }

    public function actionAssignment()
    {
        if (yii::$app->request->isAjax) {
            $ar = [];
            $post = yii::$app->request->post();
            $rightData = isset($post['role'], $post['user'], $post['operation']);
            if ($rightData) {
                $item = new ItemHelper([
                    'name' => $post['role']
                ]);
                $result = $post['operation'] === 'add' ? $item->assign($item, (int) $post['user']) : $item->revoke($item, (int) $post['user']);
                $ar['response'] = $result ? 'success' : 'error';
                $ar['message'] = Helper::str()->getDefaultMessage($ar['response']);
                die(Json::encode($ar));
            } else {
                $ar['response'] = 'error';
                $ar['message'] = Helper::str()->getDefaultMessage($ar['response']);
                die(Json::encode($ar));
            }
        }
    }

}
