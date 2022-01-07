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
        $this->assertGreaterThan(1, $res['data']['total']);
        $this->assertGreaterThan(1, $res['data']['rows']);
    }

    public function testWhereEGt()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->where('60efbf797b786d8a492bfce7', '>=', 0)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertGreaterThan(1, $res['data']['total']);
        $this->assertGreaterThan(1, $res['data']['rows']);
    }


    public function testWhereGt2()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->where('60efbf797b786d8a492bfce7', '>=', -10)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertGreaterThan(1, count($res['data']['rows']));
        $this->assertGreaterThan(1, $res['data']['total']);
    }


    public function testWhereContains()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->where('60efbf797b786d8a492bfce2', 'contains', '测试')->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertGreaterThan(1, $res['data']['rows']);
        $this->assertGreaterThan(1, $res['data']['total']);
    }

    public function testWhereNotContains()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->where('60efbf797b786d8a492bfce2', 'notContain', '测试')->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertGreaterThan(1, $res['data']['rows']);
        $this->assertGreaterThan(1, $res['data']['total']);

    }

    public function testWhereStartWith()
    {

        $title = '商品名称测试' . rand();
        $titleControlId = '60efbf797b786d8a492bfce2';
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $data = [
            ['controlId' => $titleControlId, 'value' => $title],
        ];
        $res = $mdy->table(self::workSheetTest)->insert($data);
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $rowid = $res['data'];

        $res = $mdy->table(self::workSheetTest)->where($titleControlId, 'startWith', '商品')->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertGreaterThan(1, $res['data']['rows']);
        $this->assertGreaterThan(1, $res['data']['total']);

        $mdy->table(self::workSheetTest)->delete($rowid);
    }

    public function testWhereEndWith()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->where('60efbf797b786d8a492bfce2', 'endWith', '测试')->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertEquals(0, $res['data']['total']);
        $this->assertCount(0, $res['data']['rows']);
    }

    public function testWhereEq()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $title = '商品名称测试' . rand();
        $titleControlId = '60efbf797b786d8a492bfce2';

        $res = $mdy->table(self::workSheetTest)->where($titleControlId, '=', $title)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertEquals(0, $res['data']['total']);
        $this->assertCount(0, $res['data']['rows']);


        $data = [
            ['controlId' => '60efbf797b786d8a492bfce1', 'value' => '商品标题测试' . rand()],
            ['controlId' => $titleControlId, 'value' => $title],
        ];
        $res = $mdy->table(self::workSheetTest)->insert($data);
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);


        $res = $mdy->table(self::workSheetTest)->where($titleControlId, '=', $title)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertEquals(1, $res['data']['total']);
        $this->assertCount(1, $res['data']['rows']);
    }

    public function testWhereNotEq()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $title = '商品名称测试' . rand();
        $titleControlId = '60efbf797b786d8a492bfce2';

        $res = $mdy->table(self::workSheetTest)->where($titleControlId, '!=', $title)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertGreaterThan(0, $res['data']['total']);
        $this->assertGreaterThan(0, count($res['data']['rows']));

    }

    public function testWhereNull()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $title = '商品名称测试' . rand();
        $titleControlId = '60efbf797b786d8a492bfce2';

        $res = $mdy->table(self::workSheetTest)->whereNull($titleControlId)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertEquals(0, $res['data']['total']);
        $this->assertCount(0, $res['data']['rows']);

    }

    public function testWhereNotNull()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $titleControlId = '60efbf797b786d8a492bfce2';
        $res = $mdy->table(self::workSheetTest)->whereNotNull($titleControlId)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertGreaterThan(0, $res['data']['total']);
        $this->assertGreaterThan(0, count($res['data']['rows']));

    }


    public function testWhereLt()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->where('60efbf797b786d8a492bfce7', '<', -1)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertLessThan(1, count($res['data']['rows']));
        $this->assertLessThan(1, $res['data']['total']);
    }

    public function testWhereELt()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->where('60efbf797b786d8a492bfce7', '<=', 0)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertLessThan(1, count($res['data']['rows']));
        $this->assertLessThan(1, $res['data']['total']);
    }

    public function testWhereDate()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);

        $data = [
            ['controlId' => '60efbf797b786d8a492bfce1', 'value' => '商品标题测试' . rand()],
            ['controlId' => '60efbf797b786d8a492bfce2', 'value' => '商品名称测试' . rand()],
        ];
        $res = $mdy->table(self::workSheetTest)->insert($data);
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);

        $res = $mdy->table(self::workSheetTest)->whereDate('ctime', date('Y-m-d H:i:s'))->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertGreaterThan(1, count($res['data']['rows']));
        $this->assertGreaterThan(1, $res['data']['total']);
    }

    public function testWhereDateNot()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);


        $res = $mdy->table(self::workSheetTest)->whereNotDate('ctime', date('Y-m-d H:i:s'))->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertGreaterThan(1, count($res['data']['rows']));
        $this->assertGreaterThan(1, $res['data']['total']);
    }


    public function testWhereOrTest()
    {
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);


        $res = $mdy->table(self::workSheetTest)->where('60efbf797b786d8a492bfce7', '=', 0)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $count1 = $res['data']['total'];

        $res = $mdy->table(self::workSheetTest)->whereOr('60efbf797b786d8a492bfce7', '>', 0)
            ->whereNotDate('ctime', date('Y-m-d H:i:s'))->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $count2 = $res['data']['total'];

        $this->assertGreaterThan($count1, $count2);
    }

}