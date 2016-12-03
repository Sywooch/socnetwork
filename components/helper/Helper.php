<?php

namespace app\components\helper;

use Yii;

class Helper
{
    /**
     * data helper (get,set,unset -> sessions,kookies,params)
     * @param array $params
     * @return \app\components\helper\DataHelper
     */
    public static function data($params = [])
    {
        return new DataHelper($params);
    }

    /**
     * email helper
     * @param array $params
     * @return \app\components\helper\EmailHelper
     */
    public static function email($params = [])
    {
        return new EmailHelper($params);
    }

    /**
     * user helper
     * @param array $params
     * @return app\components\helper\UserHelper
     */
    public static function user($params = [])
    {
        return new UserHelper($params);
    }

    /**
     * string helper
     * @param array $params
     * @return app\components\helper\StringHelper
     */
    public static function str($params = [])
    {
        return new StringHelper($params);
    }

    /**
     * file helper
     * @param array $params : default -> ['publicPath' => true, 'rootPath' => true]
     * @return app\components\helper\FileHelper
     */
    public static function file($params = [])
    {
        return new FileHelper($params);
    }

    /**
     * 
     * @param array $params
     * @return \app\components\helper\ftp\FtpClient
     */
    public static function ftp($params = [])
    {
        return new ftp\FtpClient();
    }
    
    public static function system()
    {
        
    }

}