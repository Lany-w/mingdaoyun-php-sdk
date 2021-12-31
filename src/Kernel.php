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
    public function getList()
    {
        $this->checkAppInit();
        $params = $this->buildRequestParams();
        $response = Http::client()->post($this->host.$this->getListApiV2, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => $params
        ]);

        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getBody(), $response->getStatusCode());
        }
        static::$worksheetId = '';
        return json_decode($response->getBody()->getContents(), true);
    }

    public function setWorkSheetMap()
    {
        $this->checkAppInit();
        $params = $this->buildRequestParams();
        $response = Http::client()->post($this->host.$this->getWorkSheetMap, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => $params
        ]);
        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getBody(), $response->getStatusCode());
        }

        $data = json_decode($response->getBody()->getContents(), true);

        MingDaoYun::$worksheetMap[MingDaoYun::$worksheetId] = $data['data'];
    }

    public function checkAppInit()
    {
        if (!$this->appKey || !$this->sign || !$this->host || !static::$worksheetId) {
            throw new InvalidArgumentException('init error:请设置appkey,sign,host,worksheetId');
        }
    }

    public function buildRequestParams()
    {
        $basic = [
            'appKey' => $this->appKey,
            'sign' => $this->sign,
            'worksheetId' => MingDaoYun::$worksheetId
        ];

        if (!empty($this->filters)) {
            $basic['filters'] = $this->filters;
            $this->filters = [];
        }

        $params = array_merge($basic, $this->getParams);
        $this->getParams = [];
        return $params;
    }

    public function buildFilters($map, $condition, $value = '')
    {
        if (is_array($map)) {
            $this->filters[] = Filter::filterTypeCreate($map);
        } else {
            if ($condition !== null && $condition !== false) {
                if (!$condition || !$value) {
                    throw new InvalidArgumentException('请求缺少参数~');
                }
            }
            $this->filters[] = Filter::filterTypeCreate($map, $condition, $value);
        }
    }
}