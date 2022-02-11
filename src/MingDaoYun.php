<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 9:29 上午
 */

namespace Lany\MingDaoYun;

use Lany\MingDaoYun\Exceptions\Exception;
use Lany\MingDaoYun\Facade\Kernel;

/**
 * Class MingDaoYun
 * @package Lany\MingDaoYun
 */
class MingDaoYun
{
    const VERSION = "1.2.1-Beta";
    //明道云APPKEY
    public static string $appKey;
    //明道云 sign
    public static string $sign;
    //明道云私有部署域名
    public static string $host;
    //worksheetId
    public static string $worksheetId = '';
    //字段对照关系
    public static array $worksheetMap = [];
    //获取列表参数
    public static array $getParams = [];
    //filters
    public static array $filters = [];
    //获取列表API
    public static string $getListUri = '/api/v2/open/worksheet/getFilterRows';
    //获取工作表结构
    public static string $getWorkSheetMapUri = '/api/v2/open/worksheet/getWorksheetInfo';
    //获取行记录详情
    public static string $getListByIdUri = '/api/v2/open/worksheet/getRowByIdPost';
    //获取关联记录
    public static string $getRelationsUri = '/api/v2/open/worksheet/getRowRelations';
    //新增记录
    public static string $addRowUri = '/api/v2/open/worksheet/addRow';
    //批量新增记录
    public static string $addRowsUri = '/api/v2/open/worksheet/addRows';
    //删除记录
    public static string $deleteUri = '/api/v2/open/worksheet/deleteRow';
    //更新单行记录
    public static string $editRowUri = '/api/v2/open/worksheet/editRow';
    //批量更新行记录
    public static string $editRowsUri = '/api/v2/open/worksheet/editRows';

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
    public function init(string $appKey, string $sign, string $host): MingDaoYun
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
     */
    public function table(string $worksheetId): MingDaoYun
    {
        self::$worksheetId = $worksheetId;
        if (!isset(self::$worksheetMap[$worksheetId])) {
            Kernel::setWorkSheetMap();
        }

        return $this;
    }

    /**
     * Notes:获取工作表数据,最多获取1000条
     * User: Lany
     * DateTime: 2021/12/30 10:12 上午
     * @return array | Exception
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
    public function with(string $rowId, string $controlId): MingDaoYun
    {
        self::$getParams['rowId'] = $rowId;
        self::$getParams['controlId'] = $controlId;
        return $this;
    }

    /**
     * Notes:获取明道云表结构
     * User: Lany
     * DateTime: 2021/12/31 2:01 下午
     * @return array
     */
    public function fieldMap() :array
    {
        return self::$worksheetMap[self::$worksheetId];
    }

    /**
     * Notes:获取关联记录
     * User: Lany
     * DateTime: 2021/12/31 1:25 下午
     * @param bool $isAll 是否获取所有关联记录(明道云默认100条)
     * @return mixed
     */
    public function relations(bool $isAll = false)
    {
        return Kernel::getRelations($isAll);
    }

    /**
     * Notes:设置工作表视图ID
     * User: Lany
     * DateTime: 2021/12/30 10:22 上午
     * @param string $viewId
     * @return $this
     */
    public function view(string $viewId): MingDaoYun
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
    public function limit(int $int = 8): MingDaoYun
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
    public function page(int $int = 1): MingDaoYun
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
    public function sort(string $field, bool $asc = true): MingDaoYun
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
     * @param string $op
     * @param string $value
     * @return $this
     */
    public function where($map, string $op = '', string $value = ''): MingDaoYun
    {
        Filter::$spliceType = 1;
        Kernel::buildFilters($map, $op, $value);
        return $this;
    }

    /**
     * Notes:使用whereOr时,必须要写在where()之前
     * User: Lany
     * DateTime: 2021/12/31 8:48 上午
     * @param $map
     * @param string $op
     * @param string $value
     * @return $this
     */
    public function whereOr($map, string $op = '', string $value = ''): MingDaoYun
    {
        Filter::$spliceType = 2;
        Kernel::buildFilters($map, $op, $value);
        return $this;
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 9:33 上午
     * @param string $field
     * @return $this
     */
    public function whereNull(string $field): MingDaoYun
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
     */
    public function whereNotNull(string $field): MingDaoYun
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
     */
    public function whereDate(string $field, string $date): MingDaoYun
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
     */
    public function whereNotDate(string $field, string $date): MingDaoYun
    {
        Filter::$spliceType = 1;
        Kernel::buildFilters($field, 18, $date);
        return $this;
    }

    /**
     * Notes:新增记录
     * User: Lany
     * DateTime: 2021/12/31 4:05 下午
     * @param array $data
     * @return array
     */
    public function insert(array $data) :array
    {
        self::$getParams['controls'] = $data;
        return Kernel::addRow();
    }

    /**
     * Notes:批量新增记录
     * User: Lany
     * DateTime: 2021/12/31 4:12 下午
     * @param array $data
     * @return array
     */
    public function create(array $data) :array
    {
        self::$getParams['rows'] = $data;
        return Kernel::addRows();
    }

    /**
     * Notes:删除记录,多个rowId用(,)逗号隔开
     * User: Lany
     * DateTime: 2021/12/31 4:17 下午
     * @param string $rowId
     * @return array
     */
    public function delete(string $rowId) :array
    {
        self::$getParams['rowId'] = $rowId;
        return Kernel::del();
    }

    /**
     * Notes:更新单选记录
     * User: Lany
     * DateTime: 2022/1/4 9:24 上午
     * @param string $rowId
     * @param array $data
     * @return array
     */
    public function update(string $rowId, array $data) :array
    {
        self::$getParams['rowId'] = $rowId;
        self::$getParams['controls'] = $data;
        return Kernel::editRow();
    }

    /**
     * Notes:批量更新行记录(明道云只支持每次更新一个控件)
     * User: Lany
     * DateTime: 2022/1/4 9:35 上午
     * @param array $rowIds
     * @param array $data
     * @return array
     */
    public function updateRows(array $rowIds, array $data) :array
    {
        self::$getParams['rowIds'] = $rowIds;
        self::$getParams['control'] = $data;
        return Kernel::editRows();
    }

    /**
     * Notes:count
     * User: Lany
     * DateTime: 2022/1/6 10:45 上午
     * @return int
     */
    public function count() :int
    {
        return Kernel::rowsCount();
    }

    /**
     * Notes:获取全部数据
     * User: Lany
     * DateTime: 2022/1/6 12:35 下午
     * @return array
     */
    public function all() :array
    {
        return Kernel::fetchAll(1000, MingDaoYun::$getListUri);
    }

}