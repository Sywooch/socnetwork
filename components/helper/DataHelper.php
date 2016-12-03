<?php

namespace app\components\helper;

use \Yii;
use \yii\web\Cookie;
use \yii\web\CookieCollection;

class DataHelper
{
//    cookies
    /**
     * set cookie value
     * @param string $name
     * @param mixed $value
     * @param array $params
     * @return boolean
     */
    public function setCookie($name, $value, $params = [])
    {
        $this->removeCookie($name);
        $default = ['name' => $name, 'value' => $value, 'expire' => time() + 86400 * 365];
        yii::$app->response->cookies->add(new Cookie(array_merge($default, $params)));
        return true;
    }

    /**
     * return cookie value if it exists
     * @param string $name
     * @param mixed $alternative (default is null) - return this value in case if no cookie name was found
     * @return mixed
     */
    public function getCookie($name, $alternative = null)
    {
        if (!is_a(Yii::$app, 'yii\web\Application'))
            return $alternative;
        $cookies = Yii::$app->request->cookies;
        return $cookies->has($name) ? $cookies->getValue($name) : $alternative;
    }

    /**
     * remove cookie value if it exists
     * @param string $name
     * @return boolean
     */
    public function removeCookie($name)
    {
        return Yii::$app->getResponse()->getCookies()->remove($name);
    }

//    cookies end
//    sessions
    /**
     * set session value
     * @param string $name
     * @param mixed $value
     * @return boolean
     */
    public function setSession($name, $value)
    {
        $this->removeSession($name);
        yii::$app->session->set($name, $value);
        return true;
    }

    /**
     * return session value if it exists
     * @param string $name
     * @param mixed $alternative (default is null) - return this value in case if no session name was found
     * @return mixed
     */
    public function getSession($name, $alternative = null)
    {
        return Yii::$app->session->has($name) ? Yii::$app->session->get($name) : $alternative;
    }

    /**
     * remove session value if it exists
     * @param string $name
     * @return boolean
     */
    public function removeSession($name)
    {
        if (Yii::$app->session->has($name))
            return Yii::$app->session->remove($name);
        return true;
    }

//    sessions end
//    params
    /**
     * set app param
     * @param type $name
     * @param type $value
     */
    public function setParam($name, $value)
    {
        $this->removeParam($name);
        yii::$app->params[$name] = $value;
        return true;
    }

    /**
     * return app param value if it exists
     * @param string $name
     * @param mixed $alternative (default is null) - return this value in case if no param name was found
     * @return mixed
     */
    public function getParam($name, $alternative = null)
    {
        if (array_key_exists($name, yii::$app->params)) {
            return yii::$app->params[$name];
        } else {
            return $alternative;
        }
    }

    /**
     * remove app param value if it exists
     * @param string $name
     * @return boolean
     */
    public function removeParam($name)
    {
        if (array_key_exists($name, yii::$app->params)) {
            unset(yii::$app->params[$name]);
        }
        return true;
    }

//    params end
}