<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 10:00 上午
 */

namespace Lany\MingDaoYun\Traits;


trait CallStatic
{
    public static function __callStatic($name, $arguments)
    {
        if (!method_exists(static::class, $name)) {
            throw new \Exception($name.'方法不存在!');
        }
        return (new static())->$name(...$arguments);
    }
}