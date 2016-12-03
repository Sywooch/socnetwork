<?php

namespace app\modules\admin\components\rbac;

use Yii;
use app\modules\admin\components\rbac\rbac;
use yii\base\InvalidParamException;
use yii\rbac\Item;

class ItemHelper extends rbac
{
    public $attributes;
    public $name;
    public $description;
    public $type;
    public $ruleName;
    public $data;
    public $createdAt;
    public $updatedAt;
    public $t = [];
    public $db;

    public function init()
    {
        $this->db = yii::$app->db;
        if (is_array($this->data) && array_key_exists('data', $this->data) && array_key_exists('t', $this->data['data'])) {
            $this->t = $this->data['data']['t'];
        }
    }

    public function getTitle($language = false)
    {
        $l = $language ? $language : yii::$app->language;
        if (array_key_exists($l, $this->t) && array_key_exists('title', $this->t[$l]) && trim($this->t[$l]['title'] != '')) {
            return $this->t[$l]['title'];
        }
        return $this->name;
    }

    public function getDesc($language = false)
    {
        $l = $language ? $language : yii::$app->language;
        if (array_key_exists($l, $this->t) && array_key_exists('title', $this->t[$l]) && trim($this->t[$l]['description']) != '') {
            return $this->t[$l]['description'];
        }
        return $this->description;
    }

    public function setAttributes($data)
    {
        $this->name = isset($data['name']) ? $data['name'] : NULL;
        $this->type = isset($data['type']) ? $data['type'] : NULL;
        $this->data = isset($data['data']) ? $data['data'] : [];
    }

    /**
     * @inheritdoc
     */
    public function remove($object)
    {
        if ($this->type == Item::TYPE_PERMISSION || $this->type == Item::TYPE_ROLE) {
            $o = $this->type == Item::TYPE_ROLE ? New \yii\rbac\Role() : New \yii\rbac\Permission();
            $o->name = $this->name;
            $o->data = ['data' => $object];
            $o->type = $this->type;
            return $this->removeItem($o);
        } else {
            throw new InvalidParamException("Removing unsupported object type.");
        }
    }

    public function update($name, $object = false)
    {
        if ($this->type == Item::TYPE_PERMISSION || $this->type == Item::TYPE_ROLE) {
            $o = $this->type == Item::TYPE_ROLE ? New \yii\rbac\Role() : New \yii\rbac\Permission();
            $o->name = $this->name;
            $o->data = ['data' => $object];
            $o->type = $this->type;
            return $this->updateItem($name, $o);
        } else {
            throw new InvalidParamException("Updating unsupported object type.");
        }
    }

    public function add($object = false)
    {
        if ($this->type == Item::TYPE_PERMISSION || $this->type == Item::TYPE_ROLE) {
            $o = $this->type == Item::TYPE_ROLE ? New \yii\rbac\Role() : New \yii\rbac\Permission();
            $o->name = $this->name;
            $o->data = ['data' => $object];
            $o->type = $this->type;
            return $this->addItem($o);
        } else {
            throw new InvalidParamException("Updating unsupported object type.");
        }
    }

}