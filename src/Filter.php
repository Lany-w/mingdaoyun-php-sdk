<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/30 3:39 下午
 */

namespace Lany\MingDaoYun;

class Filter
{
    public static int $spliceType = 1;

    /**
     * Notes:生成filter
     * User: Lany
     * DateTime: 2021/12/31 1:14 下午
     * @param $field
     * @param string $symbol
     * @param string $value
     * @return array
     */
    public static function filterTypeCreate($field, $symbol = '', $value = '')
    {
        if (is_array($field)) {
            foreach ($field as $k => $v) {
                if (is_array($v)) {
                    return self::filterTypeEnum(...$v);
                } else {
                    return self::filterTypeEnum($k, '=', $v);
                }
            }
        } else {
            return self::filterTypeEnum($field, $symbol, $value);
        }
    }

    /**
     * Notes:filter枚举
     * User: Lany
     * DateTime: 2021/12/31 1:14 下午
     * @param $field
     * @param $symbol
     * @param string $value
     * @return array
     */
    public static function filterTypeEnum($field, $symbol, $value = '')
    {
        if ($symbol === null) {
            $filterType = 7;
        } elseif ($symbol === false) {
            $filterType = 8;
        } else {
            switch ($symbol) {
                case 'contains':
                    $filterType = 1;
                    break;
                case '=':
                    $filterType = 2;
                    break;
                case 'startWith':
                    $filterType = 3;
                    break;
                case 'endWith':
                    $filterType = 4;
                    break;
                case 'notContain':
                    $filterType = 5;
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

    /**
     * Notes:获取字段dataType
     * User: Lany
     * DateTime: 2021/12/31 1:14 下午
     * @param $field
     * @return mixed
     */
    public static function getFieldDataType($field)
    {
        $map = MingDaoYun::$worksheetMap[MingDaoYun::$worksheetId]['controls'];
        $arr = array_filter($map, function ($item) use ($field) {
            return $item['controlId'] == $field;
        });
        $arr = array_pop($arr);
        return $arr['type'];
    }
}