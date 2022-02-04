<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2022/2/4 8:02 下午
 */
namespace Lany\MingDaoYun\Traits;

use Doctrine\Inflector\InflectorFactory;
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
}