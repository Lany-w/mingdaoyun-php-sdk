<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/30 10:36 上午
 */

namespace Lany\MingDaoYun\Tests;

use Lany\MingDaoYun\Facade\MingDaoYun;
use PHPUnit\Framework\TestCase;

class MingDaoYunInsertTest extends TestCase
{
    const appKey = '5922C61C66CF67AF';
    const appSecret = 'OWI4M2EwOGFmNDZiMmI5YTAwN2IxMzYyYTNkYzQ1ZjJlYzYwMjUzY2VlYWYwYTlmYmIzMzJjN2ZjOGZlMDQ5NQ==';
    const url = 'http://qtools.rinsys.com:80';
    const workSheetTest = '60efbf797b786d8a492bfcee';


    public function testInsert()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $data = [
            ['controlId' => '60efbf797b786d8a492bfce1', 'value' => '商品标题测试' . rand()],
            ['controlId' => '60efbf797b786d8a492bfce2', 'value' => '商品名称测试' . rand()],
        ];
        $res = $mdy->table(self::workSheetTest)->insert($data);
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
    }

}