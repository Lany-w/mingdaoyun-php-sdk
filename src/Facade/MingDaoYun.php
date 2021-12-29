<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 3:58 下午
 */

namespace Lany\MingDaoYun\Facade;

/**
 * Class MingDaoYun
 * @package Lany\MingDaoYun\Facade
 * @method static setUpMingDao($appKey, $sign, $host)
 */
class MingDaoYun extends Facade
{
    public static function getClass()
    {
        return 'mdy';
    }
}