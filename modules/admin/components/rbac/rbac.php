<?php

namespace app\modules\admin\components\rbac;

use yii\rbac\Role;
use yii\rbac\Permission;
use yii\rbac\Rule;
use yii\rbac\DbManager;
use \yii\helpers\Json;
use yii\rbac\Item;

class rbac extends DbManager
{
    public $items = [];

    /**
     * Populates an auth item with the data fetched from database
     * @param array $row the data from the auth item table
     * @return Item the populated auth item instance (either Role or Permission)
     */
    protected function populateItem($row)
    {
        $class = $row['type'] == Item::TYPE_PERMISSION ? Permission::className() : Role::className();

        if (!isset($row['data']) || ($data = @unserialize($row['data'])) === false) {
            $data = null;
        }
        $r = [
            'name' => $row['name'],
            'type' => $row['type'],
            'description' => $row['description'],
            'ruleName' => $row['rule_name'],
            'data' => $data,
            'createdAt' => $row['created_at'],
            'updatedAt' => $row['updated_at'],
        ];

        $this->items[$row['type']][] = new ItemHelper($r);

        return new $class($r);
    }
    
    public function getChildren($name,$object = true)
    {
        $children = parent::getChildren($name);
        $ar = [];
        if($object){
            return $children;
        }else{
            foreach ($children as $k => $v){
                $ar[] = $k;
            }
        }
        return $ar;
    }

}