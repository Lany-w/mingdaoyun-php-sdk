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

class MingDaoYunFilterTest extends TestCase
{
    const appKey = '5922C61C66CF67AF';
    const appSecret = 'OWI4M2EwOGFmNDZiMmI5YTAwN2IxMzYyYTNkYzQ1ZjJlYzYwMjUzY2VlYWYwYTlmYmIzMzJjN2ZjOGZlMDQ5NQ==';
    const url = 'http://qtools.rinsys.com:80';
    const workSheetTest = '60efbf797b786d8a492bfcee';


    public function testWhereGt()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->where('60efbf797b786d8a492bfce7', '>', 0)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertGreaterThan(1, $res['data']['rows']);
    }


}