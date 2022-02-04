<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2022/2/4 7:17 下午
 */

namespace Lany\MingDaoYun\Interfaces;

use Lany\MingDaoYun\MingDaoYun;

interface SyncAdapter
{
    public static function syncToDB($mdy, $append);

    public static function createTable($tableName, $mdy);
    public static function getTableName();
}