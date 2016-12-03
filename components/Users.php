<?php

namespace app\components;

use Yii;

class Users extends \yii\web\User
{
    public $_access;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Checks if the user can perform the operation as specified by the given permission.
     *
     * Note that you must configure "authManager" application component in order to use this method.
     * Otherwise an exception will be thrown.
     *
     * @param string $permissionName the name of the permission (e.g. "edit post") that needs access check.
     * @param array $params name-value pairs that would be passed to the rules associated
     * with the roles and permissions assigned to the user. A param with name 'user' is added to
     * this array, which holds the value of [[id]].
     * @param boolean $allowCaching whether to allow caching the result of access check.
     * When this parameter is true (default), if the access check of an operation was performed
     * before, its result will be directly returned when calling this method to check the same
     * operation. If this parameter is false, this method will always call
     * [[\yii\rbac\ManagerInterface::checkAccess()]] to obtain the up-to-date access result. Note that this
     * caching is effective only within the same request and only works when `$params = []`.
     * @return boolean whether the user can perform the operation as specified by the given permission.
     */
    public function can($permissionName = false, $params = ['module' => false], $allowCaching = true)
    {
        $identity = yii::$app->user->identity;
        if($identity && $identity->attributes['username'] == 'admin'){
            return true;
        }
        if (!$permissionName) {
            $permissionName = yii::$app->controller->module->id . '-' . yii::$app->controller->id . '-' . yii::$app->controller->action->id;
        } else {
            if (!array_key_exists('module', $params) || $params['module'] === false) {
                $permissionName = yii::$app->controller->module->id . '-' . $permissionName;
            } else {
                $permissionName = $params['module'] . '-' . $permissionName;
            }
        }
        if ($allowCaching && empty($params) && isset($this->_access[$permissionName])) {
            return $this->_access[$permissionName];
        }
        $access = $this->getAuthManager()->checkAccess($this->getId(), $permissionName, $params);
        if ($allowCaching && empty($params)) {
            $this->_access[$permissionName] = $access;
        }
        //@TODO: REMOVE TEST MODE FOR CAN 
        if (array_key_exists('test', $params))
            die($permissionName);
        return $access;
    }

}