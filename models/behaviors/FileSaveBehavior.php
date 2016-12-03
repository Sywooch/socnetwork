<?php
/**
 * Description of FileSaveBehavior
 *
 * @author postolachiserghei
 */

namespace app\models\behaviors;

use Yii;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use app\components\extend\Html;
use app\models\File;
use app\components\helper\Helper;
use app\components\widgets\uploader\UploaderWidget;
use yii\web\UploadedFile;

class FileSaveBehavior extends \yii\base\Behavior
{
    public $fileAttributes;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return[
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function beforeValidate()
    {
        if ($this->fileAttributes) {
            if (yii::$app->request->isPost) {
                foreach ($this->fileAttributes as $attribute) {
                    $this->owner->{$attribute} = $this->owner->getRuleParam($attribute, 'file', 'maxFiles', 1) > 1 ?
                            UploadedFile::getInstances($this->owner, $attribute) :
                            UploadedFile::getInstance($this->owner, $attribute);
                }
            }
        }
    }

    public function beforeSave()
    {
        $this->saveAttributeFiles();
        return true;
    }

    public function saveAttributeFiles()
    {
        if ($this->fileAttributes) {
            if (yii::$app->request->isPost) {
                $model = $this->owner;
                foreach ($this->fileAttributes as $attribute) {
                    UploaderWidget::manage([
                        'model' => $model,
                        'attribute' => $attribute,
                        'afterSave' => function($fileModel, $fileInstance) use(&$model, $attribute) {
                            if ($fileModel) {
                                if (!$model->isNewRecord && $model->oldAttributes[$attribute] != $fileModel->name) {
                                    if ($old = File::find()->where(['name' => $model->oldAttributes[$attribute]])->one()) {
                                        $old->setDeleted($model->shortClassName);
                                    }
                                }
                                $model->{$attribute} = $fileModel->name;
                            }
                            $fileModel->addDestination($model->shortClassName);
                        }]);
                            if ((int) strlen($model->{$attribute}) != 0) {
                                $this->owner->{$attribute} = $model->{$attribute};
                            } else {
                                if (!$model->isNewRecord) {
                                    $this->owner->{$attribute} = $model->oldAttributes[$attribute];
                                }
                            }
                        }
                    }
                }
            }

            public function afterDelete()
            {
                $this->deleteAttributeFiles();
            }

            /**
             * set deleted status for file record
             */
            public function deleteAttributeFiles()
            {
                if ($this->fileAttributes) {
                    foreach ($this->fileAttributes as $attribute) {
                        if ($file = File::find()->where(['name' => $this->owner->{$attribute}])->one()) {
                            $file->setDeleted($this->owner->shortClassName);
                        }
                    }
                }
            }

            /**
             * try to load file attached to current model attribute
             * @param string $attribute
             * @return File
             */
            public function getFile($attribute = null)
            {
                if ($attribute && $file = File::find()->where(['status' => File::STATUS_UPLOADED, 'name' => $this->owner->{$attribute}])->one()) {
                    return $file;
                }
                return (new File());
            }

        }        