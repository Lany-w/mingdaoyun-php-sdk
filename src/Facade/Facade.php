<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 3:47 下午
 */

namespace Lany\MingDaoYun\Facade;

use Lany\MingDaoYun\MingDaoYun;
use Lany\MingDaoYun\Kernel;

abstract class Facade implements \Lany\MingDaoYun\Interfaces\Facade
{
    protected static $classMap = [
        'mdy' => MingDaoYun::class,
        'kernel' => Kernel::class
    ];

    public static function __callStatic($name, $arguments)
    {
        if (!isset(self::$classMap[static::getClass()])) {
            throw new \Exception('facade error');
        }

        return (new self::$classMap[static::getClass()])->$name(...$arguments);
    }

}