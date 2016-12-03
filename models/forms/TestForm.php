<?php

namespace app\models\forms;

use Yii;
use app\components\extend\Model;

/**
 * Password reset request form
 */
class TestForm extends Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%test}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'file', 'maxFiles' => 10, 'skipOnEmpty' => true, 'extensions' => 'png,jpg,zip'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image' => 'Image',
            'file' => 'file',
        ];
    }

    public static function primaryKey()
    {
        return ['file'];
    }

}