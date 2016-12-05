<?php

namespace app\models;

use Yii;
use app\components\helper\Helper;
use app\components\extend\Html;
use app\components\extend\ArrayHelper;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property    string      $scheme
 * @property    string      $host
 * @property    string      $path
 * @property    string      $name
 * @property    string      $title
 * @property    string      $extension
 * @property    integer     $size
 * @property    string      $mime
 * @property    integer     $created_at
 * @property    integer     $location
 * @property    boolean     $status
 * @property    boolean     $isImage
 * @property    string      $url
 * @property    string      $owner
 * @property    string      $destination
 * @method public renderImage(array $options)
 * @method public getDestinations()
 */
/* @var $ftp \app\components\helper\ftp\FtpClient */
class File extends \app\components\extend\Model
{

    protected $ftp;
    public $helper;
    public $destination;

    const STATUS_UPLOADED = 0;
    const STATUS_DELETED = 1;
    const STATUS_IN_SYNC = 2;
    const LOCATION_LOCAL = 1;
    const LOCATION_FTP = 2;
    const SIZE_ORIGINAL = 0;
    const SIZE_LG = 1;
    const SIZE_MD = 2;
    const SIZE_SM = 3;

    /**
     * @param integer $location
     * @param boolean $withLiveEdit (return translated labels wrapped in html tag if TRUE)
     * @return array/string
     */
    public function getLocationLabels($location = false, $withLiveEdit = true)
    {
        $ar = [
            self::LOCATION_LOCAL => yii::$app->l->t('local', ['update' => $withLiveEdit]),
            self::LOCATION_FTP => yii::$app->l->t('ftp', ['update' => $withLiveEdit]),
        ];
        return $location === false ? $ar : $ar[$location];
    }

    /**
     * @param integer $status
     * @param boolean $withLiveEdit (return translated labels wrapped in html tag if TRUE)
     * @return array/string
     */
    public function getStatusLabels($status = false, $withLiveEdit = true)
    {
        $ar = [
            self::STATUS_UPLOADED => yii::$app->l->t('uploaded', ['update' => $withLiveEdit]),
            self::STATUS_IN_SYNC => yii::$app->l->t('in sync', ['update' => $withLiveEdit]),
            self::STATUS_DELETED => yii::$app->l->t('deleted', ['update' => $withLiveEdit]),
        ];
        return $status === false ? $ar : $ar[$status];
    }

    /**
     * sizes for image thumbs
     * @return array
     */
    public static function getImageSizes()
    {
        return [
            self::SIZE_LG => 'large',
            self::SIZE_MD => 'medium',
            self::SIZE_SM => 'small',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    /* relations */

    public function getOwnerUser()
    {
        return $this->hasOne(User::className(), ['id' => 'owner']);
    }

    /**
     * owner user name
     */
    public function getOwnerUserName()
    {
        if ($user = $this->ownerUser) {
            return $user->fullName;
        }
        return yii::$app->l->t('guest');
    }

    /**
     * get file destinations
     * @return FileDestination
     */
    public function getDestinations()
    {
        return $this->hasOne(FileDestination::className(), ['file_name' => 'name']);
    }

    /**
     * owner user name
     */
    public function getDestinationList($string = false)
    {
        $ar = [];
        if ($destinations = $this->getDestinations()->all()) {
            $ar = ArrayHelper::map($destinations, 'destination', 'destination');
        }
        if ($string) {
            return count($ar) > 0 ? implode(',', $ar) : implode(',', ['none']);
        }
        return count($ar) > 0 ? $ar : ['none'];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors() + [
                [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'updatedAtAttribute' => null,
                'createdAtAttribute' => 'created_at',
            ],
            'settings' => [
                'class' => settings\FileSettings::className(),
            ],
            'isImageBehavior' => [
                'class' => behaviors\FileImageBehavior::className(),
            ],
            'transferToFtp' => [
                'class' => behaviors\FileFtpBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge([
                [['location'], 'default', 'value' => self::LOCATION_LOCAL],
                [['scheme'], 'default', 'value' => 'http://'],
                [['host'], 'default', 'value' => (new Settings())->getSetting('tld')],
                [['host', 'path', 'name', 'title', 'extension', 'size', 'mime'], 'required'],
                [['owner', 'created_at', 'status', 'location'], 'safe'],
        ]);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'host' => yii::$app->l->t('host'),
            'path' => yii::$app->l->t('path'),
            'name' => yii::$app->l->t('name'),
            'title' => yii::$app->l->t('title'),
            'extension' => yii::$app->l->t('extension'),
            'size' => yii::$app->l->t('size'),
            'mime' => yii::$app->l->t('mime type'),
            'created_at' => yii::$app->l->t('created at'),
            'status' => yii::$app->l->t('status'),
            'location' => yii::$app->l->t('location'),
            'owner' => yii::$app->l->t('owner'),
            'destination' => yii::$app->l->t('destination'),
        ];
    }

    /**
     * 
     * @param string $name
     * @param object $info
     * @param string $path
     * @return mixed
     */
    public static function saveFileInfo($name, $info, $path)
    {
        $file = new File;
        $file->name = $name;
        $file->extension = pathinfo(Helper::file()->getPath($path . $name), PATHINFO_EXTENSION);
        $file->title = $info->name;
        $file->size = $info->size;
        $file->path = $path;
        $file->status = self::STATUS_UPLOADED;
        $file->mime = $info->type;
        if ($file->validate() && $file->save()) {
            return $file;
        }
        return null;
    }

    /**
     * delete file info record
     * @param integer $name
     * @return boolean
     */
    public static function deleteFileByName($name, $params)
    {
        if ($f = self::find()->where(['name' => $name])->one()) {
            if (is_array($params) && array_key_exists('beforeDelete', $params) && is_callable($params['beforeDelete'])) {
                if ($params['beforeDelete']($f)) {
                    $delete = $f->delete();
                }
            } else {
                $delete = $f->delete();
            }
            if (is_array($params) && array_key_exists('afterDelete', $params) && is_callable($params['afterDelete'])) {
                if (isset($delete) && $delete) {
                    $params['afterDelete']($f);
                }
            }
        }
        return true;
    }

    /**
     * set uploaded
     * @return type
     */
    public function setUploaded()
    {
        $this->status = self::STATUS_UPLOADED;
        return $this->validate() ? $this->save() : false;
    }

    /**
     * set in sync
     * @return type
     */
    public function setInSync()
    {
        $this->status = self::STATUS_IN_SYNC;
        return $this->validate() ? $this->save() : false;
    }

    /**
     * set as deleted record (change status)
     * @param $destination
     * @return boolean
     */
    public function setDeleted($destination = null)
    {
        if ($destination && $oneDestination = FileDestination::find()->where(['file_name' => $this->name, 'destination' => $destination])->one()) {
            if ($oneDestination->delete() && !FileDestination::find()->where(['file_name' => $this->name])->one()) {
                $this->status = self::STATUS_DELETED;
                return $this->validate() ? $this->save() : false;
            }
            return false;
        } else {
            $this->status = self::STATUS_DELETED;
            return $this->validate() ? $this->save() : false;
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if ($this->location == self::LOCATION_FTP && ($this->getSetting('transfer_to_ftp') != self::LOCATION_FTP)) {
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $bs = parent::beforeSave($insert);
        if ($insert && !yii::$app->user->isGuest) {
            $this->owner = yii::$app->user->id;
        }
        return $bs;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $ad = parent::afterDelete();
        $this->removeLocalFile();
        $this->removeFtpFile();
        FileDestination::deleteAll(['file_name' => $this->name]);
        return $ad;
    }

    /**
     * remove local files
     * @return boolean
     */
    public function removeLocalFile()
    {
        $fh = Helper::file();
        if ($this->isImage) {
            foreach (self::getImageSizes() as $k => $v) {
                $thumb = $this->path . $k . $this->name;
                $fh->rm($thumb);
            }
        }
        return $fh->rm($this->path . $this->name);
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['name'];
    }

    /**
     * 
     * @param string $fileNamePrefix
     * @return string
     */
    public function getUrl($fileNamePrefix = '')
    {
        $url = $this->scheme . $this->host . $this->path . $fileNamePrefix . $this->name;
        return $url == $fileNamePrefix ? null : $url;
    }

    /**
     * 
     * @param string $fileNamePrefix
     * @return string
     */
    public function getSource($fileNamePrefix = '')
    {
        return Helper::file()->getPath($this->path) . $fileNamePrefix . $this->name;
    }

    /**
     * check if file is image
     * @return boolean
     */
    public function getIsImage()
    {
        return in_array($this->extension, static::imageExtensions());
    }

    /**
     * render file
     * @param array $options
     * @return html
     */
    public function renderFile($options = [])
    {
        return $this->isImage ? $this->renderImage($options) : $this->getIcon($options);
    }

    /**
     * human readable file size
     * @return string
     */
    public function getFormattedSize()
    {
        return Helper::file()->bytesToSize($this->size);
    }

    /**
     * sync files
     */
    public static function syncTableFiles()
    {
        self::updateAll(['status' => self::STATUS_DELETED], 'created_at<:d AND (SELECT COUNT(*) FROM ' . FileDestination::tableName() . ' WHERE file_name=name)=0', [
            'd' => strtotime('yesterday midnight')
        ]);
        if ($files = self::find()->where(['location' => self::LOCATION_LOCAL, 'status' => self::STATUS_UPLOADED])->limit(20)->all()) {
            foreach ($files as $f) {
                /* @var $f File */
                /* @var $f behaviors\FileFtpBehavior */
                if ($f->setInSync()) {
                    $f->uploadToFtp();
                    $f->setUploaded();
                }
            }
        }
        if ($dfiles = self::find(['status' => self::STATUS_DELETED])->limit(30)->all()) {
            foreach ($dfiles as $f) {
                if ($f->status == self::STATUS_DELETED) {
                    $f->delete();
                }
            }
        }
    }

    /**
     * retiurns url do downoad file
     * @return string
     */
    public function getDownloadUrl()
    {
        return \app\components\extend\Url::to(['/site/download', 'file' => $this->name]);
    }

    /**
     * retiurns link do downoad file
     * @return html
     */
    public function getDownloadLink($options = [])
    {
        $htmlOptions = array_merge(['icon' => 'download', 'data-pjax' => 0, 'target' => '_blank',], $options);
        return Html::a('', $this->downloadUrl, $htmlOptions);
    }

    /**
     * get default image extensions
     * @param boolean $asString
     * @return mixed
     */
    public static function imageExtensions($asString = false)
    {
        $ext = ['png', 'jpg', 'jpeg', 'gif'];
        return $asString ? implode(',', $ext) : $ext;
    }

    /**
     * 
     * @param array $options
     * @return type
     */
    public static function getDefaultNoImage($options = [])
    {
        $size = array_key_exists('size', $options) ? $options['size'] : File::SIZE_LG;
        if ($size == self::SIZE_ORIGINAL) {
            $size = '';
        }
        return Html::img('/public/img/' . $size . 'no-image.png', $options);
    }

    /**
     * set destination
     * @param string $destination
     * @return boolean
     */
    public function addDestination($destination)
    {
        $model = new FileDestination();
        $model->destination = $destination;
        $model->file_name = $this->name;
        if ($model->validate()) {
            return $model->save();
        }
        return null;
    }

    /**
     * get file created date
     * @return type
     */
    public function getDate()
    {
        return Yii::$app->formatter->asDatetime($this->created_at);
    }

    /**
     * get file sizes
     * @param array $condition
     * @return integer
     */
    public function getFilesSize($condition = [])
    {
        $s = self::find()->where($condition)->select('sum(size) as size')->one()->size;
        return (int) $s > 0 ? Helper::file()->bytesToSize($s) : 0;
    }

    /**
     * download file
     * @return type
     */
    public function download()
    {
        if ($this->location == self::LOCATION_FTP) {
            header('Content-disposition: attachment; filename=' . $this->title);
            header('Content-type: ' . $this->mime);
            return readfile($this->url);
        }
        return Yii::$app->response->sendFile($this->source, $this->title);
    }

}
