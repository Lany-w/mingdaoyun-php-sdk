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
    public function getList(string $worksheetId)
    {
        $this->checkAppInit();

        if (!$worksheetId) {
            throw new InvalidArgumentException('worksheetId请求参数错误');
        }
        $params = $this->buildRequestParams($worksheetId);

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

    public function buildRequestParams(string $worksheetId)
    {
        $basic = [
            'appKey' => $this->appKey,
            'sign' => $this->sign,
            'worksheetId' => $worksheetId
        ];

        if (!empty($this->filters)) {
            $basic['filters'] = $this->filters;
            $this->filters = [];
        }
        $params = array_merge($basic, $this->getParams);
        $this->getParams = [];
        return $params;
    }

    public function buildFilters($map, $condition, $value)
    {
        if (is_array($map)) {
            $this->addFilters(Filter::filterTypeCreate($map));
        } else {
            if (!$condition || !$value) {
                throw new InvalidArgumentException('请求缺少参数~');
            }
            $this->addFilters(Filter::filterTypeCreate($map, $condition, $value));
        }
    }

    public function addFilters($filters)
    {
        foreach($filters as $v) {
            $this->filters[] = $v;
        }
    }
}