<?php

namespace app\components\extend;

use Yii;
use yii\widgets\ActiveField as BaseActiveField;
use app\components\extend\Html;

class ActiveField extends BaseActiveField
{
    public $form;
    public $model;
    public $attribute;
    public $options = ['class' => 'form-group'];
    public $template = "{label}\n{input}\n{hint}\n{error}";
    public $inputOptions = ['class' => 'form-control'];
    public $errorOptions = ['class' => 'help-block', 'encode' => false];
    public $labelOptions = ['class' => 'control-label'];
    public $hintOptions = ['class' => 'hint-block'];
    public $encode;
    public $inline;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->encode = !yii::$app->l->liveEditT;
        $this->errorOptions['encode'] = $this->encode;
    }

    /**
     * @inheritdoc
     */
    public function label($label = null, $options = [])
    {
        if ($label === false) {
            $this->parts['{label}'] = '';
            return $this;
        }

        $options = array_merge($this->labelOptions, $options);
        if ($label !== null) {
            $options['label'] = $label;
        }
        $l = !$this->encode ? Html::decode(Html::activeLabel($this->model, $this->attribute, $options)) : Html::activeLabel($this->model, $this->attribute, $options);
        $this->parts['{label}'] = $l;

        return $this;
    }

}