<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "{{%settings}}".
 *
 * @property string $key
 * @property string $value
 * @property string $model
 */
class Settings extends \app\components\extend\Model
{

    public $model_dir = '\app\models\\';
    public $obj_model;

    const FIELD_TEXT = 'textInput';
    const FIELD_PASSWORD = 'passwordInput';
    const FIELD_TEXTAREA = 'textarea';
    const FIELD_TEXTAREA_REDACTOR = 'textareaRedactor';
    const FIELD_DROPDOWN = 'dropDownList';
    const FIELD_CHECKBOX_LIST = 'checkboxList';
    const FIELD_CHECKBOX = 'checkbox';
    const FIELD_RADIO_LIST = 'radioList';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors() + [
            'settings' => [
                'class' => settings\BaseSettings::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['value'], 'string'],
                [['key', 'model'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => yii::$app->l->t('key'),
            'model' => yii::$app->l->t('model'),
        ];
    }

    /**
     * checks if array is valid and adds some default attributes if they are not defined
     * @param array $array
     * @param object $model
     * @return array
     */
    public static function normalize($array, $model)
    {
        foreach ($array as $k => $v) {
            if (!array_key_exists('value', $v)) {
                $v['value'] = null;
            }
            if (!array_key_exists('field', $v)) {
                $v['field'] = self::FIELD_TEXT;
            }
            if (!array_key_exists('model', $v)) {
                $v['model'] = $model->shortClassName;
            }
            if (!array_key_exists('items', $v)) {
                $v['items'] = ($v['field'] === self::FIELD_DROPDOWN ? ['' => yii::$app->l->t('select option', ['update' => false])] : []) + [
                    1 => yii::$app->l->t('yes', ['update' => false]),
                    0 => yii::$app->l->t('no', ['update' => false]),
                ];
            } else {
                if ($v['field'] === self::FIELD_DROPDOWN ? ['' => yii::$app->l->t('select option', ['update' => false])] : [])
                    $v['items'] = ['' => yii::$app->l->t('select option', ['update' => false])] + $v['items'];
            }
            $array[$k] = $v;
        }
        return $array;
    }

    /**
     * create model if exists (model should be placed in app\models)
     * @param string $m
     * @return object
     * @throws NotSupportedException
     */
    public function generateSettingsModel($m)
    {
        if (is_object($m)) {
            $m = $m->shortClassName;
        }
        $class = $this->model_dir . $m;
        if (!class_exists($class, true)) {
            throw new \yii\base\NotSupportedException('Not supported');
        } else {
            return (new $class);
        }
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['key', 'model'];
    }

    /**
     * 
     * @param string $key
     * @param mixed $alternative default: null
     * @return mixed
     */
    public static function getValue($key, $alternative = null)
    {
        return (new Settings)->getSetting($key, $alternative = null);
    }

}
