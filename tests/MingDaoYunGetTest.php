<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/30 10:36 上午
 */
namespace Lany\MingDaoYun\Tests;

use Lany\MingDaoYun\Exceptions\InvalidArgumentException;
use Lany\MingDaoYun\Facade\MingDaoYun;
use PHPUnit\Framework\TestCase;

class MingDaoYunGetTest extends TestCase
{
    public function testGetWithoutWorkSheetId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('worksheetId请求参数错误');
        $mdy = MingDaoYun::setUpMingDao('appKey', 'sign', 'host');
        $mdy->get();
    }

    public function testMingDaoYunInit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('init error:请设置appkey,sign,host');
        MingDaoYun::get();
    }

    public function testHttpGet()
    {
        $mdy = MingDaoYun::setUpMingDao('5922C61C66CF67AF', 'OWI4M2EwOGFmNDZiMmI5YTAwN2IxMzYyYTNkYzQ1ZjJlYzYwMjUzY2VlYWYwYTlmYmIzMzJjN2ZjOGZlMDQ5NQ==', 'http://qtools.rinsys.com:80');
        $data = $mdy->get('60efbf797b786d8a492bfcee');
        $this->assertArrayHasKey('data', $data);
    }
}