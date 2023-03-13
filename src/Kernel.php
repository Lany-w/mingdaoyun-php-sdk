<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 11:27 上午
 */
namespace Lany\MingDaoYun;

use GuzzleHttp\Exception\GuzzleException;
use Lany\MingDaoYun\Exceptions\HttpException;
use Lany\MingDaoYun\Exceptions\InvalidArgumentException;

class Kernel
{
    public static bool $isClearParams = true;

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 3:27 下午
     * @return mixed
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws GuzzleException
     */
    public function getList()
    {
        //默认最多获取1000条记录
        if (!isset(MingDaoYun::$getParams['pageSize'])) {
            MingDaoYun::$getParams['pageSize'] = 1000;
        }

        //超过1000时
        if (MingDaoYun::$getParams['pageSize'] > 1000) {
            $total = MingDaoYun::$getParams['pageSize'];
            MingDaoYun::$getParams['pageSize'] = 1000;
            static::$isClearParams = false;

            $data = $this->exec(MingDaoYun::$getListUri);
            if ($data['data']['total'] > 1000) {
                $data['data']['rows'] = $this->fetch($total, $data['data']['rows'], 1000, MingDaoYun::$getListUri);
            }
            return $data;
        }
        return $this->exec(MingDaoYun::$getListUri);
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 3:27 下午
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function findOne()
    {
        return $this->exec(MingDaoYun::$getListByIdUri);
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 3:27 下午
     * @param bool $isAll
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getRelations(bool $isAll)
    {
        if (!isset(MingDaoYun::$getParams['pageSize'])) {
            MingDaoYun::$getParams['pageSize'] = 100;
        }
        if ($isAll) {
            return $this->fetchAll(100, MingDaoYun::$getRelationsUri);
        }
        if (MingDaoYun::$getParams['pageSize'] > 100) {
            $total = MingDaoYun::$getParams['pageSize'];
            MingDaoYun::$getParams['pageSize'] = 100;
            static::$isClearParams = false;

            $data = $this->exec(MingDaoYun::$getRelationsUri);

            if ($data['data']['total'] > 100) {
                $data['data']['rows'] = $this->fetch($total, $data['data']['rows'], 100, MingDaoYun::$getRelationsUri);
            }
            return $data;
        }
        return $this->exec(MingDaoYun::$getRelationsUri);
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 3:28 下午
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function setWorkSheetMap()
    {
        $data = $this->exec(MingDaoYun::$getWorkSheetMapUri);
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
     * @throws GuzzleException
     */
    public function exec(string $uri)
    {
        $this->checkAppInit();
        $params = $this->buildRequestParams();
        //兼容处理
        if (strpos(MingDaoYun::$host, 'api.mingdao.com') !== false) {
            $uri = str_replace('/api', '', $uri);
        }
        $url = MingDaoYun::$host.$uri;

        $response = Http::client()->post($url, [
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
        if (!MingDaoYun::$appKey || !MingDaoYun::$sign || !MingDaoYun::$host || !MingDaoYun::$worksheetId) {
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
            'appKey' => MingDaoYun::$appKey,
            'sign' => MingDaoYun::$sign,
            'worksheetId' => MingDaoYun::$worksheetId
        ];

        if (!empty(MingDaoYun::$filters)) {
            $basic['filters'] = MingDaoYun::$filters;
        }

        $params = array_merge($basic, MingDaoYun::$getParams);
        if (static::$isClearParams) {
            MingDaoYun::$filters = [];
            MingDaoYun::$getParams = [];
        }

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
             Filter::filterTypeCreate($map);
        } else {

            if (is_array($condition)) {
                MingDaoYun::$filters[] = Filter::createArrayCondition($map, '=', $condition);
            } else {
                if ($condition !== null && $condition !== false) {
                    if ($condition === '' || $value === '') {
                        throw new InvalidArgumentException('请求缺少参数~');
                    }
                }
                MingDaoYun::$filters[] = Filter::filterTypeCreate($map, $condition, $value);
            }
        }
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 4:04 下午
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function addRow()
    {
        return $this->exec(MingDaoYun::$addRowUri);
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 4:12 下午
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function addRows()
    {
        return $this->exec(MingDaoYun::$addRowsUri);
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 4:19 下午
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function del()
    {
        return $this->exec(MingDaoYun::$deleteUri);
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2021/12/31 4:48 下午
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function editRow()
    {
        return $this->exec(MingDaoYun::$editRowUri);
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2022/1/4 9:29 上午
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function editRows()
    {
        return $this->exec(MingDaoYun::$editRowsUri);
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2022/1/6 10:57 上午
     * @param string $keyword
     * @return int|mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function rowsCount(string $keyword)
    {
        MingDaoYun::$getParams['keywords'] = $keyword;
        $result = $this->exec(MingDaoYun::$countRow);
        return $result['data'];
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2022/1/6 11:29 上午
     * @return mixed
     * @param $count
     * @param $uri
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function fetchAll($count, $uri)
    {
        MingDaoYun::$getParams['pageSize'] = $count;
        ini_set('memory_limit','1024M');
        static::$isClearParams = false;
        $data = $this->exec($uri);
        $total = $data['data']['total'];
        if ($total > $count) {
            $data['data']['rows'] = $this->fetch($total, $data['data']['rows'], $count, $uri);
        } else {
            MingDaoYun::$filters = [];
            MingDaoYun::$getParams = [];
        }
        static::$isClearParams = true;
        return $data;
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2022/1/6 1:37 下午
     * @param $total
     * @param $rows
     * @param $count
     * @param $uri
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     * @return array
     */
    public function fetch($total, $rows, $count, $uri)
    {
        //MingDaoYun::$getParams['pageSize'] = $count;
        $flag = ceil($total/$count);
        for ($i = 2; $i <= $flag; $i ++) {
            if ($i == $flag) static::$isClearParams = true;
            MingDaoYun::$getParams['pageIndex'] = $i;
            $result = $this->exec($uri);
            $rows = array_merge($rows, $result['data']['rows']);
        }
        return $rows;
    }

    public function getRoles()
    {
        $uri = MingDaoYun::$getRoles;
        //兼容处理
        if (strpos(MingDaoYun::$host, 'api.mingdao.com') !== false) {
            $uri = str_replace('/api', '', $uri);
        }
        $response = Http::client()->get(MingDaoYun::$host.$uri, [
            'query' => ['appKey' => MingDaoYun::$appKey, 'sign' => MingDaoYun::$sign]
        ]);
        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getBody(), $response->getStatusCode());
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    public function createRole()
    {
        return $this->exec(MingDaoYun::$createRole);
    }
}