<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/30 10:36 上午
 */

namespace Lany\MingDaoYun\Tests;

use http\Env;
use Lany\MingDaoYun\Exceptions\InvalidArgumentException;
use Lany\MingDaoYun\Facade\MingDaoOrg;
use PHPUnit\Framework\TestCase;

class MingDaoOrgStructureTest extends TestCase
{
    const appKey = '';
    const secretKey = '';
    const host = '';
    const projectId = '';


    public function testGetSubordinate()
    {
        $org = MingDaoOrg::init(self::appKey, self::secretKey, self::host, self::projectId);
        $result = $org->getMySubordinate();
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('error_code', $result);
        $this->assertGreaterThan(1, $result['data']);
    }


    public function testDelAndAddStructures()
    {
        $org = MingDaoOrg::init(self::appKey, self::secretKey, self::host, self::projectId);
        $result1 = $org->removeStructures(['cindy@rinsys.com']);
        var_dump($result1);

        $result = $org->addStructures('todd@rinsys.com', ['cindy@rinsys.com']);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('error_code', $result);
        $this->assertArrayNotHasKey('error_msg', $result);
    }


}