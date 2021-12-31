<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 9:29 上午
 */
namespace Lany\MingDaoYun;

use Lany\MingDaoYun\Exceptions\Exception;
use Lany\MingDaoYun\Exceptions\InvalidArgumentException;

/**
 * Class MingDaoYun
 * @package Lany\MingDaoYun
 */
class MingDaoYun extends Kernel
{
    //明道云APPKEY
    protected $appKey;
    //明道云 sign
    protected $sign;
    //明道云私有部署域名
    protected $host;
    //worksheetId
    public static $worksheetId = '';
    //字段对照关系
    public static $worksheetMap = [];
    //获取列表参数
    protected $getParams = [];
    //filters
    protected $filters = [];
    //获取列表API
    protected $getListApiV2 = '/api/v2/open/worksheet/getFilterRows';
    //获取工作表结构
    protected $getWorkSheetMap = '/api/v2/open/worksheet/getWorksheetInfo';
    //获取行记录详情
    protected $getListById = '/api/v2/open/worksheet/getRowByIdPost';

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
        $this->appKey = $appKey;
        $this->sign = $sign;
        $this->host = $host;

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
        $this->setWorkSheetMap();

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
        return $this->getList();
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
        $this->getParams['rowId'] = $rowId;
        return $this->findOne();
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
        $this->getParams['viewId'] = $viewId;
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
        $this->getParams['pageSize'] = $int;
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
        $this->getParams['pageIndex'] = $int;
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
        $this->getParams['sortId'] = $field;
        $this->getParams['isAsc'] = $asc;
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
        $this->buildFilters($map, $condition, $value);
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
        $this->buildFilters($map, $condition, $value);
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
        $this->buildFilters($field, null);
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
        $this->buildFilters($field, false);
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
        $this->buildFilters($field, 17, $date);
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
        $this->buildFilters($field, 18, $date);
        return $this;
    }

}