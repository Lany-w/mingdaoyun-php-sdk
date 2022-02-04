<?php
/**
 * Notes:Laravel 同步数据到本地适配器
 * User: Lany
 * DateTime: 2021/12/29 9:29 上午
 */
namespace Lany\MingDaoYun\Traits;

use Lany\MingDaoYun\Exceptions\Exception;
use Lany\MingDaoYun\Filter;
use Lany\MingDaoYun\MingDaoYun;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait LaravelAdapter
{
    use BaseSyncTrait;

    /**
     * Notes:同步数据
     * User: Lany
     * DateTime: 2022/2/4 10:55 下午
     * @param MingDaoYun $mdy
     * @param bool $append
     */
    public static function syncToDB($mdy, $append=true)
    {
        //是否存在table_name
       $tableName = static::getTableName();
       if (!$tableName) {
           //tableName不存在,查询与model相关的数据表是否存在
            $tableName = static::getModelRelationTableName();
       }
        if (!Schema::hasTable($tableName)) {
            // 表不存在, 创建数据表
            if (!static::createTable($tableName, $mdy)) {
                abort(500, '创建数据表失败!');
            }
        }
        //清空表
        if (!$append) self::truncate();
        //同步数据
        $columns = Schema::getColumnListing($tableName);

        $data = [];
        if ($mdy instanceof MingDaoYun) {
            $_data = $mdy->get();
        } elseif(isset($mdy['data']['rows'])) {
            $_data = $mdy;
        } else {
            throw new Exception("数据格式有误!");
        }

        if ($_data['error_code'] != 1) throw new Exception("获取明道云数据失败!");
        try {
            foreach($_data['data']['rows'] as $key => $val) {
                $arr = [];
                foreach($columns as $column) {
                    $v = isset($val[$column]) ? $val[$column] : '';
                    if (Filter::getFieldDataType($column) == 14 && !empty($v)) $v = json_decode($v, true)[0]['original_file_full_path'];
                    $arr[$column] = $v;
                }
                $arr['created_at'] = now();
                $arr['updated_at'] = now();
                unset($arr['id']);
                $data[] = $arr;
            }
            self::query()->insert($data);
        } catch (\Exception $exception) {
            throw new Exception("error!");
        }


        return true;
    }

    /**
     * Notes:创建数据表
     * User: Lany
     * DateTime: 2022/2/4 10:55 下午
     * @param string $tableName
     * @param MingDaoYun $mdy
     */
    public static function createTable($tableName, $mdy)
    {
        $fieldMap = $mdy->fieldMap();

        if (!isset($fieldMap['controls'])) {
            abort(500, "获取明道云表结构失败");
        }
        try {
            Schema::create($tableName, function (Blueprint $table) use ($fieldMap) {
                $table->id();
                $fieldType = static::fieldType();
                foreach($fieldMap['controls'] as $key => $val) {
                    if (array_key_exists($val['type'], $fieldType)) {
                        $field = isset($val['alias']) && !empty($val['alias']) ?: $val['controlId'];
                        $table->string($field)->nullable()->comment($val['controlName']);
                    }
                }
                $table->timestamps();
            });
            return true;
        } catch (\Exception $e) {
           throw new Exception($e->getMessage());
        }

    }

    public static function getTableName()
    {
        return (new self)->table;
    }



}