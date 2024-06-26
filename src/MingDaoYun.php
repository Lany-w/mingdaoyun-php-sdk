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
    const VERSION = "1.6.0";
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
    //获取工作表总行数
    public static string $countRow = '/api/v2/open/worksheet/getFilterRowsTotalNum';
    //获取应用角色列表
    public static string $getRoles = '/api/v1/open/app/getRoles';
    //创建应用角色
    public static string $createRole = '/api/v1/open/app/createRole';
    //删除应用角色
    public static string $deleteRole = '/api/v1/open/app/deleteRole';
    //添加应用角色成员
    public static string $addRoleMember = '/api/v1/open/app/addRoleMember';
    //移除应用角色成员
    public static string $removeRoleMember = '/api/v1/open/app/removeRoleMember';
    //退出应用
    public static string $logout = '/api/v1/open/app/quit';

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
    public function fieldMap(): array
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

    /**
     * Notes:在范围内
     * User: Lany
     * DateTime: 2023/11/27 13:20
     * @param string $field
     * @param array $condition
     * @return $this
     */
    public function whereBetween(string $field, array $condition): MingDaoYun
    {
        Filter::$spliceType = 1;
        Kernel::buildFilters($field, 'Between', $condition);
        return $this;
    }

    /**
     * Notes:不在范围内
     * User: Lany
     * DateTime: 2023/11/27 13:20
     * @param string $field
     * @param array $condition
     * @return $this
     */
    public function whereNotBetween(string $field, array $condition): MingDaoYun
    {
        Filter::$spliceType = 1;
        Kernel::buildFilters($field, 'NBetween', $condition);
        return $this;
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
     * DateTime: 2022/7/5 2:06 PM
     * @param string $field
     * @param array $condition
     * @return $this
     */
    public function whereIn(string $field, array $condition): MingDaoYun
    {
        Filter::$spliceType = 1;
        Kernel::buildFilters($field, $condition);
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

    public function dateRange(string $field, string $range): MingDaoYun
    {
        self::$filters[] = Filter::buildDateRange($field, 1, $range);
        return $this;
    }

    public function notDateRange(string $field, string $range): MingDaoYun
    {
        self::$filters[] = Filter::buildDateRange($field, 0, $range);
        return $this;
    }

    public function customize(array $filter): MingDaoYun
    {
        self::$filters[] = $filter;
        return $this;
    }


    /**
     * Notes:新增记录
     * User: Lany
     * DateTime: 2021/12/31 4:05 下午
     * @param array $data
     * @return array
     */
    public function insert(array $data): array
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
    public function create(array $data): array
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
    public function delete(string $rowId): array
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
    public function update(string $rowId, array $data): array
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
    public function updateRows(array $rowIds, array $data): array
    {
        self::$getParams['rowIds'] = $rowIds;
        self::$getParams['control'] = $data;
        return Kernel::editRows();
    }

    /**
     * Notes:count
     * User: Lany
     * DateTime: 2022/1/6 10:45 上午
     * @param string $keyword
     * @return int
     */
    public function count(string $keyword = ''): int
    {
        return Kernel::rowsCount($keyword);
    }

    /**
     * Notes:获取全部数据
     * User: Lany
     * DateTime: 2022/1/6 12:35 下午
     * @return array
     */
    public function all(): array
    {
        return Kernel::fetchAll(1000, MingDaoYun::$getListUri);
    }

    /**
     * Notes:是否触发工作流
     * User: Lany
     * DateTime: 2023/3/7 上午11:03
     * @param bool $bool
     * @return $this
     */
    public function workflow(bool $bool = true): MingDaoYun
    {
        self::$getParams['triggerWorkflow'] = $bool;
        return $this;
    }

    /**
     * Notes:是否不统计总行数以提高性能
     * User: Lany
     * DateTime: 2023/3/7 下午4:55
     * @param bool $bool
     * @return $this
     */
    public function notGetTotal(bool $bool = false): MingDaoYun
    {
        self::$getParams['notGetTotal'] = $bool;
        return $this;
    }

    /**
     * Notes:是否只返回controlId，默认false
     * User: Lany
     * DateTime: 2023/3/13 下午2:24
     * @param bool $bool
     * @return $this
     */
    public function useControlId(bool $bool = false): MingDaoYun
    {
        self::$getParams['useControlId'] = $bool;
        return $this;
    }

    /**
     * Notes:获取应用角色列表
     * User: Lany
     * DateTime: 2023/3/13 下午3:38
     * @return array
     */
    public function roles(): array
    {
        $this->table('test');
        return Kernel::getRoles();
    }

    /**
     * Notes:创建应用角色
     * User: Lany
     * DateTime: 2023/3/13 下午3:44
     * @param $name
     * @param $desc
     * @return mixed
     */
    public function createRole($name, $desc): array
    {
        $this->table('test');
        self::$getParams['name'] = $name;
        self::$getParams['desc'] = $desc;
        return Kernel::createRole();
    }

    /**
     * Notes:删除应用角色
     * User: Lany
     * DateTime: 2023/3/16 下午4:11
     * @param $roleId
     * @param $operatorId
     * @return array
     */
    public function deleteRole($roleId, $operatorId): array
    {
        $this->table('test');
        self::$getParams['roleId'] = $roleId;
        self::$getParams['operatorId'] = $operatorId;
        return Kernel::deleteRole();
    }

    /**
     * Notes:添加应用角色成员
     * User: Lany
     * DateTime: 2023/3/16 下午4:13
     * @param $roleId
     * @param $operatorId
     * @param array $userIds
     * @param array $departmentIds
     * @param array $jobIds
     * @return array
     */
    public function addRoleMember($roleId, $operatorId, array $userIds = [], array $departmentIds = [], array $jobIds = []): array
    {
        $this->table('test');
        self::$getParams['roleId'] = $roleId;
        self::$getParams['operatorId'] = $operatorId;
        self::$getParams['userIds'] = $userIds;
        self::$getParams['departmentIds'] = $departmentIds;
        self::$getParams['jobIds'] = $jobIds;
        return Kernel::addRoleMember();
    }

    /**
     * Notes:移除应用角色成员
     * User: Lany
     * DateTime: 2023/3/20 下午2:48
     * @param $roleId
     * @param $operatorId
     * @param array $userIds
     * @param array $departmentIds
     * @param $jobIds
     * @return array
     */
    public function removeRoleMember($roleId, $operatorId, array $userIds = [], array $departmentIds = [], array $jobIds = []): array
    {
        $this->table('test');
        self::$getParams['roleId'] = $roleId;
        self::$getParams['operatorId'] = $operatorId;
        self::$getParams['userIds'] = $userIds;
        self::$getParams['departmentIds'] = $departmentIds;
        self::$getParams['jobIds'] = $jobIds;
        return Kernel::removeRoleMember();
    }

    /**
     * Notes:退出应用
     * User: Lany
     * DateTime: 2023/3/20 下午2:48
     * @param $operatorId
     * @return mixed
     */
    public function logout($operatorId)
    {
        $this->table('test');
        self::$getParams['operatorId'] = $operatorId;
        return Kernel::logout();
    }

    /**
     * Notes: groupFilters
     * User: Lany
     * DateTime: 2024/3/7 10:23
     * @param callable $callback
     * @param string $spliceType
     * @return $this
     */
    public function whereGroup(callable $callback, string $spliceType = 'AND'): MingDaoYun
    {
        Kernel::groupFilters($callback, $spliceType);
        return $this;
    }

}