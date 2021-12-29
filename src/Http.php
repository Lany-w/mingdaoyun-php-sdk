<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 9:39 上午
 */

namespace Lany\MingDaoYun;

use GuzzleHttp\Client;

class Http
{
    protected static $options = [];

    public static function client()
    {
        return new Client(static::$options);
    }

    public static function setGuzzleOptions(array $options)
    {
        static::$options = $options;
        return static::class;
    }
}