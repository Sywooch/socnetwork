<?php

namespace app\components\helper;

use \Yii;
use yii\web\UploadedFile;

class FileHelper extends \yii\helpers\BaseFileHelper
{
    /**
     * if both ($publicPath & $rootPath are false then path will be converted relative to /$path  (server root))
     * @var boolean $publicPath - if is true given path will be converted relative to /serverpath/app/web/$path
     */
    public $publicPath = true;

    /**
     * if both ($publicPath & $rootPath are false then path will be converted relative to /$path  (server root))
     * @var boolean $rootPath - if this is true and $publicPath is false given path will be converted relative to /serverpath/app/$path
     */
    public $rootPath = true;

    /**
     * @param array $options key => $value
     */
    public function __construct($options = [])
    {
        foreach ($options as $k => $v) {
            if (property_exists($this, $k)) {
                $this->{$k} = $v;
            }
        }
    }

    /**
     * create directory recursive
     * @param string $path
     * @param integer $mode the permission to be set for the created directory.
     * @return boolean
     */
    public function mkdir($path, $mode = 0775)
    {
        return parent::createDirectory($this->getPath($path), $mode, true);
    }

    /**
     * remove file or folder recursive (function defines if given string is file or folder)
     * @param string $path
     * @param array $params - BaseFileHelper::removeDirectory options + "beforeDelete/afterDelete" => function ($path) {...} ]).
     * @return boolean
     */
    public function rm($path, $params = [])
    {
        $path = $this->getPath($path);
        extract($params);
        if (isset($beforeDelete) && is_callable($beforeDelete) && !$beforeDelete($path)) {
            return false;
        }
        if (is_file(rtrim($path, '/')) && file_exists(rtrim($path, '/')))
            $rm = unlink(rtrim($path, '/'));
        if (is_dir($path))
            $rm = parent::removeDirectory($path, $params);
        if (isset($afterDelete) && is_callable($afterDelete)) {
            $afterDelete($path);
        }
        return isset($rm) ? $rm : true;
    }

    /**
     * ['enctype' => 'multipart/form-data']
     * [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
     * save files:
     * @param string $path path where to save file (default is /server/app/web/$path)
     * 
     * 
     * @param string $params can be set like this: 
     * 
     * save($path,["name" => "file" / "model" => $myModel, "attribute" => "model_attribute",
     * "beforeSave/afterSave" => function ($fileName, $fileInfo) {...} ]).
     * 
     *  OR:
     * 
     * save($path,["model"=>$model, "attribute" => "file", 
     * "beforeSave/afterSave" => function ($fileName, $fileInfo) {...} ]).
     * 
     * $fileInfo - (see yii\web\UploadedFile::getInstance)
     * @return boolean
     */
    public function save($path, $params = ['name' => 'file'])
    {
        extract($params);
        $saved = null;
        $instance = isset($model, $attribute) ? UploadedFile::getInstances($model, $attribute) : (isset($name) ? UploadedFile::getInstancesByName($name) : null);
        if (!$files = $instance)
            return $saved;
        foreach ($files as $file) {
            $fileName = md5(date('Y-d-m H:i:s') . '-' . $file->baseName) . '.' . $file->extension;
            if (isset($beforeSave) && is_callable($beforeSave) && !$beforeSave($fileName, $file)) {
                continue;
            }
            $fullFilePath = $this->getPath($path) . $fileName;
            if (isset($model, $attribute)) {
                $model->{$attribute} = $model->getRuleParam($attribute, 'file', 'maxFiles', 1) == 1 ? $file : $instance;
                if (!$model->validate()) {
                    echo $model->getErrors($attribute, true);
                    return false;
                }
            }
            if ($this->mkdir($path)) {
                $saved = $file->saveAs($fullFilePath, false);
            }
            if ($saved && isset($afterSave) && is_callable($afterSave)) {
                $afterSave($fileName, $file);
            }
        }
        return $saved;
    }

    /**
     * returns root path : /root/app/$path or /root/app/web/$path
     * @param string $path
     * @return string
     */
    public function getPath($path)
    {
        if ($this->publicPath)
            return str_replace('//', '/', yii::getAlias('@app') . '/web/' . $path . '/');
        if ($this->rootPath)
            return str_replace('//', '/', yii::getAlias('@app') . '/' . $path . '/');
        return str_replace('//', '/', $path . '/');
    }

    /**
     * file extension
     * @param string $path - relative path to the file
     * @return string/null
     */
    public function extension($path)
    {
        $file = rtrim($this->getPath($path), '/');
        if (is_file($file) && file_exists($file))
            return pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * Convert bytes to human readable format
     * @param integer $size
     * @param integer $level
     * @param integer $precision
     * @param integer $showLetters
     * @return mixed
     */
    public function bytesToSize($size, $level = 0, $precision = 2, $showLetters = true)
    {
        $base = 1024;
        $unit = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $times = floor(log($size, $base));
        $result = sprintf("%." . $precision . "f", $size / pow($base, ($times + $level)));
        return $showLetters ? $result . $unit[$times + $level] : $result;
    }

}