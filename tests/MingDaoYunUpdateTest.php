<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/30 10:36 上午
 */

namespace Lany\MingDaoYun\Tests;

use Lany\MingDaoYun\Facade\MingDaoYun;
use PHPUnit\Framework\TestCase;

class MingDaoYunUpdateTest extends TestCase
{
    const appKey = '5922C61C66CF67AF';
    const appSecret = 'OWI4M2EwOGFmNDZiMmI5YTAwN2IxMzYyYTNkYzQ1ZjJlYzYwMjUzY2VlYWYwYTlmYmIzMzJjN2ZjOGZlMDQ5NQ==';
    const url = 'http://qtools.rinsys.com:80';
    const workSheetTest = '60efbf797b786d8a492bfcee';


    public function testUpdate()
    {

        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->limit(1)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertCount(1, $res['data']['rows']);
        $rowid = $res['data']['rows'][0]['rowid'];
        $titleId = '60efbf797b786d8a492bfce2';
        $old_title = $res['data']['rows'][0][$titleId];
        $random = rand(10000, 99999);
        $new_title = $old_title . $random;
        $update = [
            ['controlId' => $titleId, 'value' => $new_title],
        ];
        $updateResult = $mdy->table(self::workSheetTest)->update($rowid, $update);
        $this->assertArrayHasKey('data', $res);
        $this->assertEquals(1, $updateResult['success']);
        $findData = $mdy->table(self::workSheetTest)->find($rowid);
        $this->assertEquals($new_title, $findData['data'][$titleId]);

    }


    public function testUpdateRows()
    {

        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $res = $mdy->table(self::workSheetTest)->limit(5)->get();
        $this->assertArrayHasKey('data', $res);
        $this->assertArrayHasKey('success', $res);
        $this->assertCount(5, $res['data']['rows']);
        $rowId = [];
        foreach ($res['data']['rows'] as $row) {
            $rowId[] = $row['rowid'];
        }
        $titleId = '60efbf797b786d8a492bfce2';
        $old_title = $res['data']['rows'][0][$titleId];
        $random = rand(10000, 99999);
        $new_title = $old_title . $random;
        $update = [
            ['controlId' => $titleId, 'value' => $new_title],
        ];
        $updateResult = $mdy->table(self::workSheetTest)->updateRows($rowId, $update);
        $this->assertArrayHasKey('data', $res);
        $this->assertEquals(1, $updateResult['success']);
        $findData = $mdy->table(self::workSheetTest)->find($rowId[rand(0, 5)]);
        $this->assertEquals($new_title, $findData['data'][$titleId]);

    }

}