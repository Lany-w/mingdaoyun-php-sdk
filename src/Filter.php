<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/30 3:39 下午
 */
namespace Lany\MingDaoYun;

class Filter
{
    public static $spliceType = 1;

    public static function filterTypeCreate($field, $symbol = '', $value = '')
    {
        $filter = [];
        if (is_array($field)) {
            foreach($field as $k => $v) {
                if (is_array($v)) {
                    $filter[] = self::filterTypeEnum($v[0], $v[1], $v[2]);
                } else {
                    $filter[] = self::filterTypeEnum($k, '=', $v);
                }
            }
        } else {
            $filter[] = self::filterTypeEnum($field, $symbol, $value);
        }

        return $filter;
    }

    public static function filterTypeEnum($field, $symbol, $value='')
    {
        $filterType = 0;
        switch ($symbol) {
            case 'like':
                $filterType = 1;
                break;
            case '=':
                $filterType = 2;
                break;
            case '!=':
                $filterType = 6;
                break;
            case '>':
                $filterType = 13;
                break;
            case '>=':
                $filterType = 14;
                break;
            case '<':
                $filterType = 15;
                break;
            case '<=':
                $filterType = 16;
                break;
            default:
                $filter = [];
                break;
        }

        return [
            'controlId' => $field,
            'dataType' => 6,
            'spliceType' => static::$spliceType,
            'filterType' => $filterType,
            'value' => $value
        ];
    }
}