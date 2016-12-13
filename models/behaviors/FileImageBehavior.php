<?php

/**
 * Description of SearchBehavior
 *
 * @author postolachiserghei
 */

namespace app\models\behaviors;

use Yii;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use app\components\extend\Html;
use app\models\File;
use yii\imagine\Image;

class FileImageBehavior extends \yii\base\Behavior
{

    /**
     * @inheritdoc
     */
    public function events()
    {
        return[
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            BaseActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($event)
    {
        $this->saveThumbs();
    }

    /**
     * @inheritdoc
     */
    public function afterDelete($event)
    {
        return true;
    }

    public function afterFind($event)
    {
        return TRUE;
    }

    public function saveThumbs()
    {
        if ($this->owner->status != File::STATUS_UPLOADED || !$this->owner->isImage || $this->owner->location != File::LOCATION_LOCAL || $this->areThumsCreated()) {
            return true;
        }
        foreach ($this->owner->imageSizes as $k => $v) {
            $w = $this->owner->getSetting('imageWidth' . $k);
            $h = $this->owner->getSetting('imageHeight' . $k);
            Image::thumbnail($this->owner->source, $w, $h)->save($this->owner->getSource($k));
        }
    }

    public function areThumsCreated()
    {
        foreach ($this->owner->imageSizes as $k => $v) {
            if (!is_file($this->owner->getSource($k))) {
                return false;
            }
        }
        return true;
    }

    /**
     * render image
     * @param type $options
     * @return string
     */
    public function renderImage($options = [])
    {
        $options['data']['url'] = $this->owner->url;
        if (!array_key_exists('title', $options))
            $options['title'] = $this->owner->title;
        if (!array_key_exists('alt', $options))
            $options['alt'] = $this->owner->title;

        $size = array_key_exists('size', $options) ? $options['size'] : File::SIZE_LG;

        $src = $this->owner->getUrl(($size > File::SIZE_ORIGINAL ? $size : ''));
        if ($size == File::SIZE_ORIGINAL) {
            $options['wrapIntoBg'] = true;
        }

        return ($src !== $size && $src !== NULL) ? Html::img($src, $options) : File::getDefaultNoImage($options);
    }

    public function getIcon($options = [])
    {
        $ico = 'file';
        $color = '#888';
        switch ($this->owner->extension) {
            case 'pdf':
                $ico = 'file-pdf-o';
                $color = '#C30C08';
                break;
            case 'txt':
                $ico = 'file-text';
                break;
            case 'doc':
            case 'pages':
            case 'docx':
                $color = 'rgb(20, 151, 175)';
                $ico = 'file-word-o';
                break;
            case 'wma':
            case 'mp3':
                $color = '#F4805C';
                $ico = 'file-audio-o';
                break;
            case 'mov':
            case 'wmv':
            case 'vob':
            case 'flv':
            case 'webm':
            case '3gp':
            case 'mp4':
            case 'mp4p':
            case 'mp4v':
            case 'avi':
            case 'mkv':
                $color = 'rgb(129, 93, 183)';
                $ico = 'file-video-o';
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                $color = 'rgb(79, 175, 75)';
                $ico = 'file-image-o';
                break;
            case 'rar':
            case 'zip':
                $color = '#D09C36';
                $ico = 'file-archive-o';
                break;
        }
        $size = array_key_exists('size', $options) ? $options['size'] : File::SIZE_MD;
        switch ($size) {
            case File::SIZE_SM:
                Html::addCssClass($options, 'fs-2');
            case File::SIZE_MD:
                Html::addCssClass($options, 'fs-4');
            case File::SIZE_LG:
                Html::addCssClass($options, 'fs-5');
            case File::SIZE_ORIGINAL:
                Html::addCssClass($options, 'fs-6');
        }
        $options['title'] = $this->owner->extension;
        return Html::ico($ico, array_merge(['style' => 'color:' . $color . '!important'], $options));
    }

}
