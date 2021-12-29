<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 9:29 上午
 */
namespace Lany\MingDaoYun;

use Lany\MingDaoYun\Traits\CallStatic;

class MingDaoYun
{
    use CallStatic;

    protected $appKey;

    protected $sign;

    public function __construct()
    {

    }

    protected function setUpMingDao($appKey, $sign)
    {
        $this->appKey = $appKey;
        $this->sign = $sign;

    }
}