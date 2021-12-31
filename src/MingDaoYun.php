<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 9:29 上午
 */
namespace Lany\MingDaoYun;

use Lany\MingDaoYun\Exceptions\Exception;
use Lany\MingDaoYun\Exceptions\InvalidArgumentException;
use Lany\MingDaoYun\Facade\Kernel;

/**
 * Class MingDaoYun
 * @package Lany\MingDaoYun
 */
class MingDaoYun
{
    //明道云APPKEY
    public static $appKey;
    //明道云 sign
    public static $sign;
    //明道云私有部署域名
    public static $host;
    //worksheetId
    public static $worksheetId = '';
    //字段对照关系
    public static $worksheetMap = [];
    //获取列表参数
    public static $getParams = [];
    //filters
    public static $filters = [];
    //获取列表API
    public static $getListUri = '/api/v2/open/worksheet/getFilterRows';
    //获取工作表结构
    public static $getWorkSheetMapUri = '/api/v2/open/worksheet/getWorksheetInfo';
    //获取行记录详情
    public static $getListByIdUri = '/api/v2/open/worksheet/getRowByIdPost';
    //获取关联记录
    public static $getRelationsUri = '/api/v2/open/worksheet/getRowRelations';

    public function __construct()
    {
    }

    /**
     * Notes:明道云基础配置
     * User: Lany
     * DateTime: 2021/12/29 12:45 下午
     * @param string $appKey
     * @param string $sign
     * @param string $host
     * @return $this
     */
    public function init(string $appKey,string $sign,string $host)
    {
        self::$appKey = $appKey;
        self::$sign = $sign;
        self::$host = $host;

        return $this;
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 10:47 上午
     * @param string $worksheetId
     * @return $this
     * @throws Exceptions\HttpException
     */
    public function table(string $worksheetId)
    {
        self::$worksheetId = $worksheetId;
        Kernel::setWorkSheetMap();

        return $this;
    }

    /**
     * Notes:获取工作表数据
     * User: Lany
     * DateTime: 2021/12/30 10:12 上午
     * @return array | Exception
     * @throws Exceptions\HttpException
     */
    public function get()
    {
        return Kernel::getList();
    }

    /**
     * Notes:获取行记录详情
     * User: Lany
     * DateTime: 2021/12/31 12:45 下午
     * @param string $rowId
     * @return mixed
     */
    public function find(string $rowId)
    {
        self::$getParams['rowId'] = $rowId;
        return Kernel::findOne();
    }

    /**
     * Notes:设置关联记录
     * User: Lany
     * DateTime: 2021/12/31 1:23 下午
     * @param string $rowId
     * @param string $controlId
     * @return $this
     */
    public function with(string $rowId, string $controlId)
    {
        self::$getParams['rowId'] = $rowId;
        self::$getParams['controlId'] = $controlId;
        return $this;
    }

    /**
     * Notes:获取明道云表结构
     * User: Lany
     * DateTime: 2021/12/31 2:01 下午
     * @return mixed
     */
    public function fieldMap()
    {
        return self::$worksheetMap[self::$worksheetId];
    }

    /**
     * Notes:获取关联记录
     * User: Lany
     * DateTime: 2021/12/31 1:25 下午
     * @return mixed
     */
    public function relations()
    {
        return Kernel::getRelations();
    }

    /**
     * Notes:设置工作表视图ID
     * User: Lany
     * DateTime: 2021/12/30 10:22 上午
     * @param string $viewId
     * @return $this
     */
    public function view(string $viewId)
    {
        self::$getParams['viewId'] = $viewId;
        return $this;
    }

    /**
     * Notes:设置查询行数
     * User: Lany
     * DateTime: 2021/12/30 10:22 上午
     * @param int $int
     * @return $this
     */
    public function limit(int $int = 8)
    {
        self::$getParams['pageSize'] = $int;
        return $this;
    }

    /**
     * Notes:设置页码
     * User: Lany
     * DateTime: 2021/12/30 10:23 上午
     * @param int $int
     * @return $this
     */
    public function page(int $int = 1)
    {
        self::$getParams['pageIndex'] = $int;
        return $this;
    }

    /**
     * Notes:设置排序字段,是否升序
     * User: Lany
     * DateTime: 2021/12/30 2:53 下午
     * @param string $field
     * @param bool $asc
     * @return $this
     */
    public function sort(string $field, bool $asc = true)
    {
        self::$getParams['sortId'] = $field;
        self::$getParams['isAsc'] = $asc;
        return $this;
    }

    /**
     * Notes:设置查询条件
     * User: Lany
     * DateTime: 2021/12/30 3:47 下午
     * @param mixed $map
     * @param string $condition
     * @param string $value
     * @return $this
     * @throws InvalidArgumentException
     */
    public function where($map, string $condition='', string $value='')
    {
        Filter::$spliceType = 1;
        Kernel::buildFilters($map, $condition, $value);
        return $this;
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 8:48 上午
     * @param $map
     * @param string $condition
     * @param string $value
     * @return $this
     * @throws InvalidArgumentException
     */
    public function whereOr($map, string $condition='', string $value='')
    {
        Filter::$spliceType = 2;
        Kernel::buildFilters($map, $condition, $value);
        return $this;
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 9:33 上午
     * @param string $field
     * @return $this
     * @throws InvalidArgumentException
     */
    public function whereNull(string $field)
    {
        Filter::$spliceType = 1;
        Kernel::buildFilters($field, null);
        return $this;
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 9:38 上午
     * @param string $field
     * @return $this
     * @throws InvalidArgumentException
     */
    public function whereNotNull(string $field)
    {
        Filter::$spliceType = 1;
        Kernel::buildFilters($field, false);
        return $this;
    }

    public function whereBetween()
    {
        //todo
    }

    public function whereNotBetween()
    {
        //todo
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 10:20 上午
     * @param string $field
     * @param string $date
     * @return $this
     * @throws InvalidArgumentException
     */
    public function whereDate(string $field,string $date)
    {
        Filter::$spliceType = 1;
        Kernel::buildFilters($field, 17, $date);
        return $this;
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 10:21 上午
     * @param string $field
     * @param string $date
     * @return $this
     * @throws InvalidArgumentException
     */
    public function whereNotDate(string $field,string $date)
    {
        Filter::$spliceType = 1;
        Kernel::buildFilters($field, 18, $date);
        return $this;
    }

}