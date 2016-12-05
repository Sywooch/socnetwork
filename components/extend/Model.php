<?php

namespace app\components\extend;

use yii;
use app\components\extend\Html;

class Model extends \yii\db\ActiveRecord
{
    public $shortClassName;
    public $wasValidated;

    public function init()
    {
        $this->shortClassName = (new \ReflectionClass($this))->getShortName();
        return parent::init();
    }

    const EVENT_SETTINGS_ASIGN = 'asignSettingsForModel';

    /**
     * mod to get model errors as string as well
     * @param string $attribute
     * @param boolean $asString
     */
    public function getErrors($attribute = null, $asString = false)
    {
        $errors = parent::getErrors($attribute);
        if (!$asString)
            return $errors;
        $tmp = '';
        foreach ($errors as $k => $v) {
            $tmp.= Html::tag('div', ' - ' . $v[0]);
        }
        return $tmp;
    }

    /**
     * adds current language at the end of the attribute label (if record language is different from the current)
     * @param array $labels
     * @return array
     */
    public function LanguageNoteLabels($labels)
    {
        $ret = [];
        $language = '';
        if (yii::$app->l->multi) {
            $l = yii::$app->request->get('l');
            $language = ($l && $l != yii::$app->language) ? ' (' . yii::$app->l->languages[$l] . ')' : '';
        }
        foreach ($labels as $k => $v) {
            $ret[$k] = $v . $language;
        }
        return $ret;
    }

    public function fakeRule()
    {
        
    }

    /**
     * get model rule param value 
     * @param string $attribute
     * @param string $method
     * @param string $param
     * @param mixed $alternative
     * @return mixed
     */
    public function getRuleParam($attribute, $method, $param, $alternative = null)
    {
        foreach ($this->rules() as $k => $v) {
            if (($v[0] == $attribute || (is_array($v[0]) && in_array($attribute, $v[0]))) && $v[1] == $method) {
                if (array_key_exists($param, $v)) {
                    $result = $v[$param];
                }
                break;
            }
        }
        return isset($result) ? $result : $alternative;
    }

    //@TODO: after validate remove 
    public function afterValidate()
    {
        $av = parent::afterValidate();
        $this->wasValidated = true;
        return $av;
    }

}