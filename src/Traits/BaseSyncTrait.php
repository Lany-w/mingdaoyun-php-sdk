<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2022/2/4 8:02 下午
 */
namespace Lany\MingDaoYun\Traits;

use Doctrine\Inflector\InflectorFactory;
use Lany\MingDaoYun\Exceptions\Exception;
use Lany\MingDaoYun\Filter;
use Lany\MingDaoYun\MingDaoYun;

trait BaseSyncTrait
{

    public static function uncamelize(string $class) :string
    {
        $inflector = InflectorFactory::create()->build();
        return $inflector->pluralize($inflector->tableize($class)); // model_name
    }

    public static function getModelRelationTableName() :string
    {
        $class = explode('\\', self::class);
        $className = array_pop($class);
        return static::uncamelize($className);
    }

    public static function fieldType() :array
    {
        $map = [
            2 => '文本框',
            6 => '数值',
            11 => '单选',
            14 => '附件',
            15 => '日期',
            16 => '日期和时间',
            26 => '成员',
            30 => '关联表字段',
            31 => '公式',
            32 => '文本组合',
            33 => '自动编号',

        ];
        return $map;
    }

    public static function getTableName()
    {
        return (new self)->table;
    }

    public static function insertDataHandle($mdy, $columns, $framework=1)
    {
        $data = [];
        if ($mdy instanceof MingDaoYun) {
            $_data = $mdy->get();
        } elseif(isset($mdy['data']['rows'])) {
            $_data = $mdy;
        } else {
            throw new Exception("数据格式有误!");
        }
        if ($_data['error_code'] != 1) throw new Exception("获取明道云数据失败!");

        foreach($_data['data']['rows'] as $key => $val) {
            $arr = [];
            foreach($columns as $column) {
                $v = isset($val[$column]) ? $val[$column] : '';
                if (Filter::getFieldDataType($column) == 14 && !empty($v)) $v = json_decode($v, true)[0]['original_file_full_path'];
                if (Filter::getFieldDataType($column) == 26 && !empty($v)) $v = $v['fullname'];
                $arr[$column] = $v;
            }
            if ($framework !== 1) {
                $arr['created_at'] = now();
                $arr['updated_at'] = now();
            } else {
                $arr['created_at'] = date("Y-m-d H:i:s", time());
            }

            unset($arr['id']);
            $data[] = $arr;
        }
        return $data;
    }
}