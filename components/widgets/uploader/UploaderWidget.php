<?php

namespace app\components\widgets\uploader;

use yii;
use yii\base\Widget;
use yii\web\View;
use yii\helpers\Json;
use yii\base\InvalidConfigException;
use app\components\helper\Helper;
use app\components\Request;
use app\components\extend\Html;
use yii\web\JsExpression;
use app\components\extend\Model;
use app\models\File;
use app\models\Settings;

class UploaderWidget extends Widget
{

    public $ajax;
    public $action;
    public $limit = 100; //max nr of files
    public $maxSize = 500; //max file size in mb
    public $extensions; //list of extensions allowed, ex: 'png,jpg,gif' 
    public $showThumbs = false;
    public $model;
    public $template;
    public $attribute;
    public $name;
    public $options;
    public $pluginOptions;
    public $containerOptions = [];
    /* plugin events (!!!anonymous functions are available only in such way : (new function(){ .... })  !!!) */
    public $onRemove; /* variabiles:  $selector, $el, $id */
    public $onSelect; /* variabiles: $selector, $i */
    public $onBeforeSelect; /* variabiles : $selector, $files, $l, $p, $o, $s */
    public $onEmpty;  /* variabiles : $selector, $p, $o, $s */
    public $afterShow; /* variabiles: $selector, $l, $p, $o, $s */
    /* @var $onSuccess after file is uploaded (triggers only with ajax) */
    public $onSuccess; /* variabiles : $selector, $data, $textStatus, $jqXHR */
    /* @var $beforeSend before file is uploaded (triggers only with ajax) */
    public $onBeforeSend; /* variabiles :  $selector,$el, $l, $p, $o, $s, $id, $jqXHR, $settings */

    /**
     * @return html
     */
    public function run()
    {
        if ($hasModel = $this->hasModel()) {
            $this->limit = $this->model->getRuleParam($this->attribute, 'file', 'maxFiles', 1);
            $this->maxSize = Helper::file()->bytesToSize($this->model->getRuleParam($this->attribute, 'file', 'maxSize', 5), 0, 0, false);
            $this->extensions = $this->model->getRuleParam($this->attribute, 'file', 'extensions', 'doc,docx,pdf,png,jpg,gix');
            $this->id = Html::getInputId($this->model, $this->attribute);
        }
        $this->containerOptions['id'] = $this->id . '-container';
        $this->setDefaultAction();
        $this->validate();
        $this->defaultPluginOptions();
        $this->registerScript();
        Html::addCssClass($this->options, 'hidden uploader-input');
        if ($hasModel) {
            if (is_array($this->model{$this->attribute})) {
                $this->model{$this->attribute} = implode(',', $this->model{$this->attribute});
            }
            $input = Html::activeFileInput($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::fileInput($this->name, null, $this->options);
        }
        return Html::tag('div', $input, $this->containerOptions);
    }

    /**
     * check if main params are set
     * @throws InvalidConfigException
     */
    public function validate()
    {
        if ($this->name === null && !$this->hasModel()) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
    }

    /**
     * combine default plugin options with custom options
     * @return array
     */
    public function defaultPluginOptions()
    {
        $options = [
            'limit' => $this->limit, //nr of max files
            'maxSize' => $this->maxSize, //max file size in mb
            'addMore' => false,
            'extensions' => $this->extensions ? explode(',', $this->extensions) : null, //extensions
            'templates' => [
                'box' => $this->render('template/_box', ['uploader' => $this]),
                'item' => $this->render('template/_item', ['uploader' => $this]),
                'removeConfirmation' => false,
                'itemAppendToEnd' => false,
                '_selectors' => [
                    'list' => '.jFiler-items-list',
                    'item' => '.jFiler-item',
                    'progressBar' => '.bar',
                    'remove' => '.jFiler-item-trash-action'
                ],
            ],
            'showThumbs' => !$this->ajax ? $this->showThumbs : true,
            'captions' => [
                'button' => yii::$app->l->t('Choose Files'),
                'feedback' => yii::$app->l->t('Choose files To Upload'),
                'feedback2' => yii::$app->l->t('files were chosen'),
                'drop' => yii::$app->l->t('Drop file here to Upload'),
                'removeConfirmation' => yii::$app->l->t('delete') . ' ?',
                'errors' => [
                    'filesLimit' => yii::t('yii', 'You can upload at most {limit, number} {limit, plural, one{file} other{files}}.', ['limit' => $this->limit]),
                    'filesType' => yii::t('yii', 'Only files with these extensions are allowed: {extensions}.', ['extensions' => $this->extensions]),
                    'filesSize' => yii::t('yii', 'The file "{file}" is too big. Its size cannot exceed {formattedLimit}.', [
                        'file' => '{{fi-name}}',
                        'formattedLimit' => '{{fi-maxSize}}MB'
                    ]),
                    'filesSizeAll' => yii::$app->l->t("Files you've choosed are too large! Please upload files up to {max-size} MB.", [
                        'max-size' => '{{fi-maxSize}}'
                    ])
                ]
            ],
            'dragDrop' => [
                'dragEnter' => null,
                'dragLeave' => null,
                'drop' => null,
            ],
        ];
        if ($this->ajax) {
            if (!$this->template) {
                $this->template = '_changeInput-drag';
            }
            $options['uploadFile'] = [
                'url' => $this->action,
                'type' => 'POST',
                'enctype' => 'multipart/form-data',
                'beforeSend' => $this->registerBeforeSendScript(),
                'statusCode' => null,
                'onProgress' => null,
                'onComplete' => null,
                'error' => new JsExpression('function($jqXHR, $textStatus, $errorThrown){ console.log($textStatus); }'),
                'success' => $this->registerSuccessScript(),
            ];
            $options['changeInput'] = $this->render('template/_changeInput-drag', ['uploader' => $this]);
        } else {
            if (!$this->template) {
                $this->template = '_single1';
            }
            $options['changeInput'] = $this->render('template/' . $this->template, ['uploader' => $this]);
        }

        $options['onRemove'] = $this->registerRemoveScript();
        $options['onSelect'] = $this->registerOnSelectScript();
        $options['beforeSelect'] = $this->registerBeforeSelectScript();
        $options['onEmpty'] = $this->registerOnEmptyScript();
        $options['afterShow'] = $this->registerAfterShowScript();

        if ($this->pluginOptions && is_array($this->pluginOptions) && count($this->pluginOptions) > 0)
            $this->pluginOptions = array_merge($options, $this->pluginOptions);

        $options['headers'][Request::CSRF_HEADER] = \Yii::$app->getRequest()->getCsrfToken();
        $options['params'][Yii::$app->getRequest()->csrfParam] = \Yii::$app->getRequest()->getCsrfToken();

        $this->pluginOptions = $options;
    }

    /**
     * default action for Ajax upload & remove file
     */
    public function setDefaultAction()
    {
        $c = yii::$app->controller;
        $m = $c->module->id;
        $action = ($m == 'frontend' ? '' : '/' . $m) . '/' . $c->id . '/' . $c->action->id;
        if ($this->ajax && !$this->action)
            $this->action = $action;
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return ($this->model instanceof Model && $this->attribute !== null);
    }

    /**
     * catch and manage save/delete actions
     * @param array $params : 
     * "name" => "file" / "model" => $myModel, "attribute" => "model_attribute",
     * can be set function => "beforeSave/afterSave" => function ($fileModel) {...} ])., "beforeDelete/afterDelete" => function ($fileModel) {...} ]).
     */
    public static function manage($params = [])
    {
        $path = '/public/uploads/' . (new Settings())->getSetting('tld') . '/' . date('y') . '/' . date("W") . '/';
        $params['afterSave'] = function($fileName, $info) use ($params, $path) {
            $fileModel = File::saveFileInfo($fileName, $info, $path);
            $as = (array_key_exists('afterSave', $params)) ? $params['afterSave'] : null;
            if (is_callable($as))
                $as($fileModel, $info);
            if (yii::$app->request->isAjax && $fileModel && !yii::$app->request->isPjax)
                die(Json::encode(['name' => $fileModel->name, 'info' => $info]));
        };
        Helper::file()->save($path, $params);
        $fileName = yii::$app->request->post('fiRemoveFileAjax');
        if ($fileName && trim($fileName) !== '') {
            $params['afterDelete'] = function($path) use ($params, $fileName) {
                $ad = (array_key_exists('afterDelete', $params)) ? $params['afterDelete'] : null;
                if (is_callable($ad))
                    $ad($fileModel);
                if (yii::$app->request->isAjax && !yii::$app->request->isPjax)
                    die(Json::encode(['response' => 'success']));
            };
            File::deleteFileByName($fileName, $params);
        }
    }

    /**
     * register plugin script
     */
    public function registerScript()
    {
        UploaderWidgetAssets::register(yii::$app->controller->view);
        $plugin = '$("#' . $this->id . '").filer(' . Json::encode($this->pluginOptions) . ');';
        yii::$app->controller->view->registerJs($plugin, View::POS_END, $this->containerOptions['id']);
    }

    public function registerSuccessScript()
    {
        $js = 'var $selector = $("#' . $this->containerOptions['id'] . '");';
        $js .= 'fiSuccessEvent($selector, $data, $textStatus, $jqXHR) ;' . $this->onSuccess . '; ';
        return new JsExpression('function($data, $textStatus, $jqXHR){ ' . $js . ' }');
    }

    public function registerBeforeSendScript()
    {
        $js = 'var $selector = $("#' . $this->containerOptions['id'] . '");';
        $js .= 'fiBeforeSendEvent($selector,$el, $l, $p, $o, $s, $id, $jqXHR, $settings) ;' . $this->onBeforeSend . '; ';
        return new JsExpression('function($el, $l, $p, $o, $s, $id, $jqXHR, $settings){' . $js . '}');
    }

    public function registerRemoveScript()
    {
        $js = 'var $selector = $("#' . $this->containerOptions['id'] . '");';
        $js .= 'fiRemoveEvent($selector, $el, $id);' . $this->onRemove . '; ';
        return new JsExpression('function($el,$id){ ' . $js . ' }');
    }

    public function registerBeforeSelectScript()
    {
        $js = 'var $selector = $("#' . $this->containerOptions['id'] . '");';
        $js .= 'fiBeforeSelectEvent($selector, $files, $l, $p, $o, $s); ' . $this->onBeforeSelect . ';';
        return new JsExpression('function($files, $l, $p, $o, $s){ ' . $js . ' return true;}');
    }

    public function registerOnEmptyScript()
    {
        $js = 'var $selector = $("#' . $this->containerOptions['id'] . '");';
        $js .= 'fiOnEmptyEvent($selector, $p, $o, $s) ;' . $this->onEmpty . '; ';
        return new JsExpression('function($p, $o, $s){ ' . $js . ' return true;}');
    }

    public function registerOnSelectScript()
    {
        $js = 'var $selector = $("#' . $this->containerOptions['id'] . '");';
        $js .= 'fiOnSelectEvent($selector, $i) ;' . $this->onSelect . ';';
        return new JsExpression('function($i){ ' . $js . ' }');
    }

    public function registerAfterShowScript()
    {
        $js = 'var $selector = $("#' . $this->containerOptions['id'] . '");';
        $js .= 'fiAfterShowEvent($selector, $l, $p, $o, $s) ;' . $this->afterShow . '; ';
        return new JsExpression('function($l, $p, $o, $s){ ' . $js . ' }');
    }

}

?>