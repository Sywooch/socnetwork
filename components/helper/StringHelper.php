<?php

namespace app\components\helper;

use yii;

class StringHelper
{

    /**
     * will replace all tags: {attribute} with object attribute values
     * @param string $string
     * @param object/array $data
     * @param array $matchBetween default ['{','}']
     * @return string
     */
    public function replaceTagsWithDatatValues($string, $data, $matchBetween = ['{', '}'])
    {
        preg_match_all('#{(.*?)}#', $string, $matches);
        $params = [];
        if (count($matches) > 0) {
            if (array_key_exists(1, $matches) && is_array($matches[1])) {
                foreach ($matches[1] as $k)
                    $params[$matchBetween[0] . $k . $matchBetween[1]] = $k;
            }
        }
        foreach ($params as $k => $v) {
            try {
                $string = is_object($data) ?
                        (@$data->{$v} !== null ? str_replace($k, $data->{$v}, $string) : $string) :
                        (@$data[$v] !== null ? str_replace($k, $data[$v], $string) : $string);
            } catch (Exception $exc) {
                continue;
            }
        }
        return $string;
    }

    /**
     * get default messages by type ("success", "warning", "error")
     * @param string $type
     * @return string
     */
    public function getDefaultMessage($type)
    {
        $ar = [
            'success' => yii::$app->l->t('Operation succeeded'),
            'warning' => yii::$app->l->t('Operation failed'),
            'error' => yii::$app->l->t('Operation failed'),
        ];
        return @$ar[$type] ? $ar[$type] : $type;
    }

}
