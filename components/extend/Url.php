<?php

namespace app\components\extend;

use Yii;
use app\models\Seo;

class Url extends \yii\helpers\Url
{

    /**
     * @inheritdoc
     */
    public static function to($route = '', $scheme = false)
    {
        return self::checkSeoLink(parent::to($route, $scheme));
    }

    /**
     * check if url is part of SEO
     * @param string $url
     * @return string
     */
    public static function checkSeoLink($url)
    {
        $seo = Seo::find()->joinWith('seoT')->where(['url' => $url, 'language_id' => yii::$app->language])->one();
        return $seo ? $seo->t->alias : $url;
    }

}
