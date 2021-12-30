<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 11:27 上午
 */
namespace Lany\MingDaoYun;

use Lany\MingDaoYun\Exceptions\HttpException;
use Lany\MingDaoYun\Exceptions\InvalidArgumentException;

class Kernel
{
    public function getList($worksheetId)
    {
        $this->checkAppInit();

        if (!$worksheetId) {
            throw new InvalidArgumentException('worksheetId请求参数错误');
        }
        $params = [
            'appKey' => $this->appKey,
            'sign' => $this->sign,
            'worksheetId' => $worksheetId
        ];

        $response = Http::client()->post($this->host.$this->getListApiV2, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => $params
        ]);

        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getBody(), $response->getStatusCode());
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function checkAppInit()
    {
        if (!$this->appKey || !$this->sign || !$this->host) {
            throw new InvalidArgumentException('init error:请设置appkey,sign,host');
        }
    }

}