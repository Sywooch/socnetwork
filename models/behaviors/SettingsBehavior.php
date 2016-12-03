<?php
/**
 * Description of SeoBehavior
 *
 * @author postolachiserghei
 */

namespace app\models\behaviors;

use Yii;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use app\models\Settings;
use app\components\extend\Model as BaseModel;

class SettingsBehavior extends \yii\base\Behavior
{
    public $settings;
    public $settingsAreChanged;

    /**
     * get settings value by key
     * @param string $key
     * @param mixed $alternative
     * @return mixed
     */
    public function getSetting($key, $alternative = null)
    {
        $this->owner->trigger(BaseModel::EVENT_SETTINGS_ASIGN);
        $modelName = $this->owner->shortClassName;
        if ($settings = Settings::find()->where(['model' => (string) $modelName, 'key' => $key])->one()) {
            return @unserialize($settings->value);
        } else {
            if (is_array($this->settings) && array_key_exists($key, $this->settings)) {
                return $this->settings[$key]['value'];
            }
        }
        return $alternative;
    }

    /**
     * load settings
     * @return array
     */
    public function getAllSettings()
    {
        $this->owner->trigger(BaseModel::EVENT_SETTINGS_ASIGN);
        $modelName = $this->owner->shortClassName;
        if ($settings = Settings::find()->where(['model' => (string) $modelName])->all()) {
            foreach ($settings as $s) {
                if (is_array($this->settings) && array_key_exists($s->key, $this->settings)) {
                    $this->settingsAreChanged = true;
                    $this->settings[$s->key]['value'] = @unserialize($s->value);
                }
            }
        }
        return $this->settings;
    }

}