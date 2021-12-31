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
        return $this->exec($this->getListApiV2);
    }

    public function findOne()
    {
        return $this->exec($this->getListById);
    }

    public function getRelations()
    {
        return $this->exec($this->getRelationsUri);
    }

    public function setWorkSheetMap()
    {
        $data = $this->exec($this->getWorkSheetMap);
        MingDaoYun::$worksheetMap[MingDaoYun::$worksheetId] = $data['data'];
    }

    /**
     * Notes:执行Http请求
     * User: Lany
     * DateTime: 2021/12/31 1:11 下午
     * @param string $uri
     * @return mixed
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function exec(string $uri)
    {
        $this->checkAppInit();
        $params = $this->buildRequestParams();
        $response = Http::client()->post($this->host.$uri, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => $params
        ]);
        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getBody(), $response->getStatusCode());
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Notes:检查初始化参数
     * User: Lany
     * DateTime: 2021/12/31 1:13 下午
     * @throws InvalidArgumentException
     */
    public function checkAppInit()
    {
        if (!$this->appKey || !$this->sign || !$this->host || !static::$worksheetId) {
            throw new InvalidArgumentException('init error:请设置appkey,sign,host,worksheetId');
        }
    }

    /**
     * Notes:生成请求参数
     * User: Lany
     * DateTime: 2021/12/31 1:13 下午
     * @return array
     */
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

    /**
     * Notes:创建filters参数
     * User: Lany
     * DateTime: 2021/12/31 1:13 下午
     * @param $map
     * @param $condition
     * @param string $value
     * @throws InvalidArgumentException
     */
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