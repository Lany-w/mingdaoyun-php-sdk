<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 9:29 上午
 */
namespace Lany\MingDaoYun;

use Lany\MingDaoYun\Exceptions\Exception;

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

    public function get(string $worksheetId)
    {
        return $this->getList($worksheetId);
    }


}