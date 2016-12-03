<?php

namespace app\components\helper;

use Yii;

class EmailHelper
{
    /**
     * 
     * @param string $to
     * @param string $subject
     * @param array $render ['view'=>'viewFileName' , 'params' => ['param1'=>'value1','param2'=>'value2']]
     * @param array $from
     * @return type
     */
    public static function send($to, $subject, $render, $from = null)
    {
        if (!$from) {
            $from = (new DataHelper)->getParam('adminEmail', 'gaftonsifon@yandex.com');
        }
        if (!array_key_exists('view', $render))
            return false;
        return \Yii::$app->mailer->compose($render['view'], (array_key_exists('params', $render) ? $render['params'] : []))
                        ->setFrom($from)
                        ->setTo($to)
                        ->setSubject($subject)
                        ->send();
    }

}