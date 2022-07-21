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

    public static function createArrayCondition($field, $symbol, $value)
    {
        return self::filterTypeEnum($field, $symbol, $value, true);
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
    public static function filterTypeEnum($field, $symbol, $value = '', $isArray = false)
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
                case 'RCEq':
                    $filterType = 24;
                    break;
                case 'RCNe':
                    $filterType = 25;
                    break;
                default:
                    $filterType = $symbol;
                    break;
            }
        }

        $example = [
            'controlId' => $field,
            'dataType' => self::getFieldDataType($field),
            'spliceType' => static::$spliceType,
            'filterType' => $filterType,
        ];

        if ($isArray) {
            $example['values'] = $value;
        } else {
            $example['value'] = $value;
        }
        return $example;
    }

    public static function buildDateRange($field, $flag,$range): array
    {
        $example = [
            'controlId' => $field,
            'dataType' => self::getFieldDataType($field),
            'spliceType' => 1,
            'filterType' => $flag ? 17 : 18,
        ];

        switch ($range) {
            case 'Today':
                $enum = 1;
                break;
            case 'Yesterday':
                $enum = 2;
                break;
            case 'Tomorrow':
                $enum = 3;
                break;
            case 'ThisWeek':
                $enum = 4;
                break;
            case 'LastWeek':
                $enum = 5;
                break;
            case 'NextWeek':
                $enum = 6;
                break;
            case 'ThisMonth':
                $enum = 7;
                break;
            case 'LastMonth':
                $enum = 8;
                break;
            case 'NextMonth':
                $enum = 9;
                break;
            case 'ThisQuarter':
                $enum = 12;
                break;
            case 'LastQuarter':
                $enum = 13;
                break;
            case 'NextQuarter':
                $enum = 14;
                break;
            case 'ThisYear':
                $enum = 15;
                break;
            case 'LastYear':
                $enum = 16;
                break;
            case 'NextYear':
                $enum = 17;
                break;
            case 'Last7Day':
                $enum = 21;
                break;
            case 'Last14Day':
                $enum = 22;
                break;
            case 'Last30Day':
                $enum = 23;
                break;
            case 'Next7Day':
                $enum = 31;
                break;
            case 'Next14Day':
                $enum = 32;
                break;
            case 'Next33Day':
                $enum = 33;
                break;
            default:
                $enum = 0;

        }
        $example['dateRange'] = $enum;
        return $example;
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
            return ($item['controlId'] == $field || (isset($item['alias']) && $item['alias'] == $field));
        });
        $arr = array_pop($arr);
        return $arr['type'] ?? null;
    }
}