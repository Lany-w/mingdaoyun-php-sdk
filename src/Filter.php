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
        if (is_array($field)) {
            foreach($field as $k => $v) {
                if (is_array($v)) {
                    return self::filterTypeEnum($v[0], $v[1], $v[2]);
                } else {
                    return self::filterTypeEnum($k, '=', $v);
                }
            }
        } else {
            return self::filterTypeEnum($field, $symbol, $value);
        }
    }

    public static function filterTypeEnum($field, $symbol, $value='')
    {
        if ($symbol === null) {
            $filterType = 7;
        } elseif ($symbol === false) {
            $filterType = 8;
        } else {
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
                case 17:
                    $filterType = 17;
                    break;
                case 18:
                    $filterType = 18;
                    break;
                default:
                    $filterType = 0;
                    break;
            }
        }


        return [
            'controlId' => $field,
            'dataType' => self::getFieldDataType($field),
            'spliceType' => static::$spliceType,
            'filterType' => $filterType,
            'value' => $value
        ];
    }

    public static function getFieldDataType($field)
    {
        $map = MingDaoYun::WORK_SHEET_MAP[MingDaoYun::$worksheetId][MingDaoYun::$worksheetId];
        print_r($map);exit;
    }
}