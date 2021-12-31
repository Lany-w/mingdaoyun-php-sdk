<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/30 10:36 上午
 */

namespace Lany\MingDaoYun\Tests;

use Lany\MingDaoYun\Facade\MingDaoYun;
use PHPUnit\Framework\TestCase;

class MingDaoYunSortTest extends TestCase
{
    const appKey = '5922C61C66CF67AF';
    const appSecret = 'OWI4M2EwOGFmNDZiMmI5YTAwN2IxMzYyYTNkYzQ1ZjJlYzYwMjUzY2VlYWYwYTlmYmIzMzJjN2ZjOGZlMDQ5NQ==';
    const url = 'http://qtools.rinsys.com:80';
    const workSheetTest = '60efbf797b786d8a492bfcee';


    public function testSort()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->limit(1)->get();
        $total = $res['data']['total'];
        $count = $total;
        $field = '60efbf797b786d8a492bfce7';
        $res = $mdy->table(self::workSheetTest)->limit($count)->sort($field)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertCount($count, $res['data']['rows']);

        $data = $res['data'];
        $firstData = $data['rows'][0];
        $lastData = $data['rows'][$count - 1];
        $this->assertGreaterThan(intval($firstData[$field]), intval($lastData[$field]));

        $res = $mdy->table(self::workSheetTest)->limit($count)->sort($field, false)->get();
        $data = $res['data'];
        $firstData = $data['rows'][0];
        $lastData = $data['rows'][$count - 1];

        $this->assertGreaterThan(intval($lastData[$field]), intval($firstData[$field]));

    }

}