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
    const appKey = '5922C61C66CF67AF';
    const appSecret = 'OWI4M2EwOGFmNDZiMmI5YTAwN2IxMzYyYTNkYzQ1ZjJlYzYwMjUzY2VlYWYwYTlmYmIzMzJjN2ZjOGZlMDQ5NQ==';
    const url = 'http://qtools.rinsys.com:80';
    const workSheetTest = '60efbf797b786d8a492bfcee';

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
        $mdy = MingDaoYun::setUpMingDao(self::appKey, self::appSecret, self::url);
        $data = $mdy->get(self::workSheetTest);
        $this->assertArrayHasKey('data', $data);
    }

    public function testLimit()
    {
        $mdy = MingDaoYun::setUpMingDao(self::appKey, self::appSecret, self::url);
        $count = 2;
        $data = $mdy->limit($count)->get(self::workSheetTest);
        if ($data['data']['total'] > $count) {
            $this->assertArrayHasKey('data', $data);
            $this->assertCount($count, $data['data']['rows']);
        } else {
            $this->assertArrayHasKey('data', $data);
            $this->assertCount($data['data']['total'], $data['data']['rows']);
        }
    }

    public function testPage()
    {
        $mdy = MingDaoYun::setUpMingDao(self::appKey, self::appSecret, self::url);
        $res = $mdy->limit(1)->get(self::workSheetTest);
        $total = $res['data']['total'];
        $pageSize = 100;
        $lastPage = ceil($total / $pageSize) + 1;
        $data = $mdy->limit($pageSize)->page($lastPage)->get(self::workSheetTest);
        $this->assertArrayHasKey('data', $data);
        $this->assertCount(0, $data['data']['rows']);
    }

    public function testSort()
    {
        $mdy = MingDaoYun::setUpMingDao(self::appKey, self::appSecret, self::url);
        $res = $mdy->limit(1)->get(self::workSheetTest);
        $total = $res['data']['total'];
        $count = $total;
        $field = '60efbf797b786d8a492bfce7';
        $res = $mdy->limit($count)->sort($field)->get(self::workSheetTest);
        $this->assertArrayHasKey('data', $res);
        $this->assertCount($count, $res['data']['rows']);

        $data = $res['data'];
        $firstData = $data['rows'][0];
        $lastData = $data['rows'][$count - 1];
        $this->assertGreaterThan(intval($firstData[$field]), intval($lastData[$field]));

        $res = $mdy->limit($count)->sort($field, false)->get(self::workSheetTest);
        $data = $res['data'];
        $firstData = $data['rows'][0];
        $lastData = $data['rows'][$count - 1];

        $this->assertGreaterThan(intval($lastData[$field]), intval($firstData[$field]));

    }

}