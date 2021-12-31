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
    //获取列表参数
    protected $getParams = [];
    //filters
    protected $filters = [];
    //获取列表API
    protected $getListApiV2 = '/api/v2/open/worksheet/getFilterRows';

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
    public function setUpMingDao(string $appKey,string $sign,string $host)
    {
        $this->appKey = $appKey;
        $this->sign = $sign;
        $this->host = $host;

        return $this;
    }

    /**
     * Notes:获取工作表数据
     * User: Lany
     * DateTime: 2021/12/30 10:12 上午
     * @param string $worksheetId
     * @return array | Exception
     */
    public function get(string $worksheetId = '')
    {
        return $this->getList($worksheetId);
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

    public function whereNull()
    {
        //todo
    }

    public function whereNotNull()
    {
        //todo
    }

    public function whereBetween()
    {
        //todo
    }

    public function whereNotBetween()
    {
        //todo
    }

}