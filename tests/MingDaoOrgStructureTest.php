<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/30 10:36 上午
 */

namespace Lany\MingDaoYun\Tests;

use GuzzleHttp\Exception\GuzzleException;
use http\Env;
use Lany\MingDaoYun\Exceptions\HttpException;
use Lany\MingDaoYun\Exceptions\InvalidArgumentException;
use Lany\MingDaoYun\Facade\MingDaoOrg;
use PHPUnit\Framework\TestCase;

class MingDaoOrgStructureTest extends TestCase
{
    private static $config;

    public static function setUpBeforeClass(): void
    {
        self::$config = [
            'appKey' => $_ENV['APP_KEY'],
            'secretKey' => $_ENV['SECRET_KEY'],
            'host' => $_ENV['HOST'],
            'projectId' => $_ENV['PROJECT_ID'],

        ];
    }

    public function testGetSubordinate()
    {
        $org = MingDaoOrg::init(self::$config['appKey'], self::secretKey, self::$config['host'], self::$config['projectId']);
        $result = $org->getMySubordinate();
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('error_code', $result);
        $this->assertGreaterThan(1, $result['data']);
    }


    public function testDelAndAddStructures()
    {
        $org = MingDaoOrg::init(self::$config['appKey'], self::$config['secretKey'], self::$config['host'], self::$config['projectId']);
        $result1 = $org->removeStructures(['cindy@rinsys.com']);
//        var_dump($result1);

        $result = $org->addStructures('todd@rinsys.com', ['cindy@rinsys.com']);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('error_code', $result);
        $this->assertArrayNotHasKey('error_msg', $result);
    }


    /**
     * @throws HttpException
     * @throws GuzzleException
     * @throws InvalidArgumentException
     */
    public function testUserAdd()
    {
        $org = MingDaoOrg::init(self::$config['appKey'], self::$config['secretKey'], self::$config['host'], self::$config['projectId']);
        $data = $org->upsertUser([
            'corpUserId' => 'todd.lee@rinsys.com',
            'name' => 'Todd Test',
            'email' => 'todd.lee@rinsys.com',
            'mobilePhone' => '',
            'contactPhone' => '',

        ]);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['code'], 1);
    }


    public function testUsersAdd()
    {
        $org = MingDaoOrg::init(self::$config['appKey'], self::$config['secretKey'], self::$config['host'], self::$config['projectId']);
        $data = $org->upsertUsers([
            [
                'corpUserId' => 'todd@rinsys.com',
                'name' => 'Todd Test',
                'email' => 'todd@rinsys.com',
                'mobilePhone' => '',
                'contactPhone' => '',

            ]
        ]);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['code'], 1);
    }


    public function testUsersRemove()
    {
        $org = MingDaoOrg::init(self::$config['appKey'], self::$config['secretKey'], self::$config['host'], self::$config['projectId']);
        $data = $org->removeUser('todd.lee@rinsys.com');
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($data['code'], 1);
    }

}