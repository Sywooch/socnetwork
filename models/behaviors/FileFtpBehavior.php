<?php
/**
 * Description of SearchBehavior
 *
 * @author postolachiserghei
 */

namespace app\models\behaviors;

use yii;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use app\components\extend\Html;
use app\models\File;
use app\components\helper\Helper;

class FileFtpBehavior extends \yii\base\Behavior
{
    /**
     * @inheritdoc
     */
    protected $ftp;
    protected $ftp_host;
    protected $ftp_username;
    protected $ftp_password;
    protected $ftp_dir;
    protected $ftp_ssl;
    protected $ftp_port;

    /**
     * 
     */
    public function initFtp()
    {
        $this->ftp_host = $this->owner->getSetting('ftp_host');
        $this->ftp_username = $this->owner->getSetting('ftp_username');
        $this->ftp_password = $this->owner->getSetting('ftp_password');
        $this->ftp_dir = $this->owner->getSetting('ftp_dir');
        $this->ftp_ssl = $this->owner->getSetting('ftp_ssl') == 'yes' ? true : false;
        $this->ftp_port = $this->owner->getSetting('ftp_port');
        $ftp = Helper::ftp();
        $ftp->connect($this->ftp_host, $this->ftp_ssl, $this->ftp_port);
        $this->ftp = $ftp->login($this->ftp_username, $this->ftp_password);
    }

    public function uploadToFtp()
    {
        if ($this->owner->getSetting('transfer_to_ftp') != File::LOCATION_FTP || $this->owner->location == File::LOCATION_FTP) {
            return true;
        }

        if (!$this->ftp) {
            $this->initFtp();
        }
        if ($this->ftp) {
            $this->ftp->chmod(0777, $this->ftp_dir);
            if (!$this->ftp->isDir($this->ftp_dir . $this->owner->path)) {
                @$this->ftp->mkdir($this->ftp_dir . $this->owner->path, true);
                $this->ftp->chmod(0777, $this->ftp_dir . $this->owner->path);
            }
            if (is_file($this->owner->source) && $this->ftp->put($this->ftp_dir . $this->owner->path . $this->owner->name, $this->owner->source, FTP_ASCII)) {
                $this->owner->location = File::LOCATION_FTP;
                $this->owner->host = $this->ftp_host;
                $this->owner->scheme = $this->ftp_ssl ? 'https://' : 'http://';
                if ($this->owner->isImage) {
                    foreach (File::getImageSizes() as $k => $v) {
                        $thumb = $this->ftp_dir . $this->owner->path . $k . $this->owner->name;
                        if (is_file($this->owner->getSource($k)))
                            $this->ftp->put($this->ftp_dir . $this->owner->path . $k . $this->owner->name, $this->owner->getSource($k), FTP_ASCII);
                    }
                }
                if ($this->owner->save()) {
                    return $this->owner->removeLocalFile();
                }
                return true;
            }
        }
    }

    public function removeFtpFile()
    {
        if ($this->owner->getSetting('transfer_to_ftp') != File::LOCATION_FTP || $this->owner->location != File::LOCATION_FTP)
            return true;
        if (!$this->ftp) {
            $this->initFtp();
        }
        $dir = $this->ftp_dir;

        if ($this->owner->isImage) {
            foreach (File::getImageSizes() as $k => $v) {
                $thumb = $dir . $this->owner->path . $k . $this->owner->name;
                $this->ftp->remove($thumb);
            }
        }
        return $this->ftp->remove($dir . $this->owner->path . $this->owner->name);
    }

}