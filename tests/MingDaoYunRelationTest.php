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

class MingDaoYunRelationTest extends TestCase
{
    const appKey = '5922C61C66CF67AF';
    const appSecret = 'OWI4M2EwOGFmNDZiMmI5YTAwN2IxMzYyYTNkYzQ1ZjJlYzYwMjUzY2VlYWYwYTlmYmIzMzJjN2ZjOGZlMDQ5NQ==';
    const url = 'http://qtools.rinsys.com:80';
    const workSheetTest = '60efbf797b786d8a492bfcee';


    public function testRelations()
    {
        $relationControlId = '60efbf797b786d8a492bfced';
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $data = $mdy->table(self::workSheetTest)->whereNotNull($relationControlId)->get();
        $this->assertGreaterThan(1, $data['data']['rows']);
        $firstDataRowId = $data['data']['rows'][0]['rowid'];
        $relationsData = $mdy->table(self::workSheetTest)->with($firstDataRowId, $relationControlId)->relations();
        $this->assertArrayHasKey('data', $relationsData);
        $this->assertArrayHasKey('success', $relationsData);
        $this->assertArrayHasKey('error_code', $relationsData);
        $this->assertGreaterThan(1, $relationsData['data']['rows']);

    }

    public function testRelationAllRows()
    {
        $relationControlId = '60efbf797b786d8a492bfced';
        $mdy = MingDaoYun::init(self::appKey, self::appSecret, self::url);
        $data = $mdy->table(self::workSheetTest)->whereNotNull($relationControlId)->get();
        $this->assertGreaterThan(1, $data['data']['rows']);
        $firstDataRowId = $data['data']['rows'][0]['rowid'];
        $relationsData = $mdy->table(self::workSheetTest)->with($firstDataRowId, $relationControlId)->relations(true);
        $this->assertArrayHasKey('data', $relationsData);
        $this->assertArrayHasKey('success', $relationsData);
        $this->assertArrayHasKey('error_code', $relationsData);
        $this->assertGreaterThan(1, $relationsData['data']['rows']);

    }
}