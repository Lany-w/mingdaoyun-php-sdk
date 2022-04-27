<?php

namespace Lany\MingDaoYun\Facade;

/**
 * Class MingDaoOrg
 * @package Lany\MingDaoYun\Facade
 * @method \Lany\MingDaoYun\MingDaoOrg init(string $appKey, string $secretKey, string $host, string $projectId) static
 *
 */
class MingDaoOrg extends Facade
{
    public static function getClass()
    {
        return 'mdyorg';
    }
}