<?php
/**
 * Notes:ThinkPHP 同步数据到本地适配器
 * User: Lany
 * DateTime: 2022/2/4 7:15 下午
 */
namespace Lany\MingDaoYun\Traits;

use Lany\MingDaoYun\Exceptions\Exception;
use think\App;

trait ThinkPHPAdapter {
    use BaseSyncTrait;

    /**
     * Notes:同步数据到本地数据库
     * User: Lany
     * DateTime: 2022/2/5 12:35 下午
     * @param $mdy
     * @param bool $append
     * @return string
     */
    public static function syncToDB($mdy, $append=true)
    {
        //模型tableName
        $tableName = static::getTableName();
        if (!$tableName) {
            //tableName不存在,查询与model相关的数据表是否存在
            $tableName = static::getModelRelationTableName();
        }
        $_DB = static::_DB();
        $exist = $_DB::query("show tables like '{$tableName}'");

        if (!$exist) {
            // 表不存在, 创建数据表
            if (!static::createTable($tableName, $mdy)) {
                abort(500, '创建数据表失败!');
            }
        }
        if (!$append) $_DB::query("delete from {$tableName}");
        //获取所有field
        //插入数据
        $columns = array_column($_DB::query("select COLUMN_NAME from information_schema.COLUMNS where table_name = '{$tableName}'"), 'COLUMN_NAME');
        try {
            $data = static::insertDataHandle($mdy, $columns);
            $_DB::name($tableName)->data($data)->limit(1000)->insertAll($data);
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }
        return true;
    }

    /**
     * Notes:创建数据表(TP6使用model时会检测表是否存在,5.1不会#吐槽)
     * User: Lany
     * DateTime: 2022/2/5 4:32 下午
     * @param $tableName
     * @param $mdy
     * @return bool
     * @throws Exception
     */
    public static function createTable($tableName, $mdy)
    {
        $fieldMap = $mdy->fieldMap();
        if (!isset($fieldMap['controls'])) {
            throw new Exception("获取明道云表结构失败");
        }
        $sql = " CREATE TABLE IF NOT EXISTS `$tableName` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,";

        foreach($fieldMap['controls'] as $key => $val) {
            $fieldType = static::fieldType();
            if (array_key_exists($val['type'], $fieldType)) {
                $field = isset($val['alias']) && !empty($val['alias']) ?: $val['controlId'];
                $sql .= " `$field` VARCHAR (255) DEFAULT NULL COMMENT '{$val['controlName']}',";
            }
        }
        $sql .= "
        `created_at` VARCHAR (45) NOT NULL COMMENT '时间',
         PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='表';";
        $_DB::execute($sql);
        return true;
    }

    /**
     * Notes:兼容TP5.1和TP6 (吐槽)
     * User: Lany
     * DateTime: 2022/2/5 4:24 下午
     */
    public static function _DB()
    {
        $version = (App::VERSION)[0];
        if ($version == 5) {
            return '\think\Db';
        } elseif ($version == 6) {
            return '\think\facade\Db';
        }
    }

}