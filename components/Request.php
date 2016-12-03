<?php

namespace app\components;

use yii;
use app\models\t\SeoT;
use app\models\Seo;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use app\models\Settings;

class Request extends \yii\web\Request
{

    public $web;
    public $backendUrl;
    private $_queryParams;
    public $seoAlias;
    public $allLanguages;
    public $seo;
    public $clearUri;

    public function setLanguageOptions()
    {
        $this->allLanguages = yii::$app->l->languages;
        yii::$app->l->multi = count($this->allLanguages) > 1 ? true : false;
        yii::$app->language = yii::$app->l->default;
        if (!yii::$app->language) {
            yii::$app->language = 'en';
        }
    }

    public function clearUrl($url)
    {
        if (strpos($url, '?') !== false) {
            $url = substr($url, 0, strpos($url, "?"));
        }
        return trim(rawurldecode(mb_convert_encoding($url, "UTF-8", "auto")));
    }

    public function checkSeo($route, $get)
    {
        $this->clearUri = $this->clearUrl($this->url);
        $query = SeoT::find();
        $query->select(Seo::tableName() . ".*," . SeoT::tableName() . '.*');
        $query->joinWith(['seo'])
                ->where('(alias=:a)', [
                    'a' => $this->clearUri,
                    'l_all' => implode(',', array_keys($this->allLanguages))
        ]);
        $query->andWhere([SeoT::tableName() . '.language_id' => yii::$app->language]);
        $query->orderBy([new Expression('FIND_IN_SET(language_id, :l_all)')]);
        $seoT = $query->one();
        if ($seoT) {
            $this->seo = $seoT;
            $seoURL = $seoT->seo->url;
            $get = $this->getParamsFromString($seoURL);
            $route = $this->seoAlias ? $this->seoAlias : $seoURL;
            $get = array_merge($get, $this->getParamsFromString(yii::$app->request->url), (yii::$app->l->multi ? ['l' => $seoT->language_id] : []));
            $_GET = $get;
        } else {
            if (!yii::$app->request->isAjax) {
                $u = trim(rawurldecode($seoT ? $seoT->seo->url : $this->url));
                $query = Seo::find();
                $query->joinWith(['seoT']);
                $query->where(['OR', ['url' => $u], ['alias' => $u]]);
                $seo = $query->one();
                $pass = $seo ? SeoT::find()->where(['seo_id' => $seo->primaryKey, 'language_id' => yii::$app->language])->count() > 0 : false;
                if ($pass && (!$seoT || ($seoT && ($seoT->seo->url != $seo->url)))) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header('location: ' . $seo->alias);
                    exit();
                }
            }
        }
        return [$route, $get];
    }

    public function getParamsFromString($string)
    {
        $p = explode('?', $string);
        if (count($p) > 1 && array_key_exists(1, $p)) {
            $this->seoAlias = $p[0];
            parse_str($p[1], $params);
            return $params;
        }
        return [];
    }

    public function resolve()
    {
        $this->setLanguageOptions();
        $result = Yii::$app->getUrlManager()->parseRequest($this);
        if ($result !== false) {
            list ($route, $params) = $result;
            if ($this->_queryParams === null) {
                $_GET = $params + $_GET; // preserve numeric keys
            } else {
                $this->_queryParams = $params + $this->_queryParams;
            }
            return $this->checkSeo($route, $this->getQueryParams());
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }

    public function getBaseUrl()
    {
        return str_replace($this->web, "", parent::getBaseUrl()) . $this->backendUrl;
    }

    public function resolvePathInfo()
    {
        if ($this->getUrl() === $this->backendUrl) {
            return "";
        } else {
            return parent::resolvePathInfo();
        }
    }

    public function createUrl($url = [], $absolute = false)
    {
        if ($absolute)
            return Yii::$app->urlManager->createAbsoluteUrl($url);
        return Yii::$app->urlManager->createUrl($url);
    }

}
