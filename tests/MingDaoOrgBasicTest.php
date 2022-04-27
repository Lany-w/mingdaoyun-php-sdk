<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/30 10:36 上午
 */

namespace Lany\MingDaoYun\Tests;

use Lany\MingDaoYun\Exceptions\InvalidArgumentException;
use Lany\MingDaoYun\Facade\MingDaoOrg;
use PHPUnit\Framework\TestCase;

class MingDaoOrgBasicTest extends TestCase
{
    const appKey = '5f06a0bbabd2679f';
    const secretKey = '987c5fd8fe13ff0ff54ba98a9d1c482';
    const host = 'https://apps.worxuite.com/api';
    const projectId = '508873dd-76d3-4aa1-8aaa-d899f08f4664';


    public function testGetySubordinate()
    {
        $org = MingDaoOrg::init(self::appKey, self::secretKey, self::host, self::projectId);
        $result = $org->getMySubordinate();
        var_dump($result);
    }


}