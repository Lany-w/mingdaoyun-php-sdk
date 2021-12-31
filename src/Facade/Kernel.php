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
 * @method static setWorkSheetMap()
 * @method static getList()
 * @method static buildFilters($map, $condition='', $value='')
 * @method static findOne()
 * @method static getRelations()
 * @method static addRow()
 * @method static addRows()
 * @method static del()
 * @method static editRow()
 */
class Kernel extends Facade
{
    public static function getClass()
    {
        return 'kernel';
    }
}