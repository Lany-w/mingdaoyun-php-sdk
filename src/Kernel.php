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
                $data['data']['rows'] = $this->fetch($total, $data['data']['rows'], 1000);
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
        $size = MingDaoYun::$getParams['pageSize'] ?? 0;
        if ($isAll) {
            return $this->fetchAll(100, MingDaoYun::$getRelationsUri);
        }
        if ($size > 100) {
            $total = MingDaoYun::$getParams['pageSize'];
            MingDaoYun::$getParams['pageSize'] = 100;
            static::$isClearParams = false;

            $data = $this->exec(MingDaoYun::$getRelationsUri);

            if ($data['data']['total'] > 100) {
                $data['data']['rows'] = $this->fetch($total, $data['data']['rows'], 100);
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
        $response = Http::client()->post(MingDaoYun::$host.$uri, [
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
            MingDaoYun::$filters[] = Filter::filterTypeCreate($map);
        } else {
            if ($condition !== null && $condition !== false) {
                if ($condition === '' || $value === '') {
                    throw new InvalidArgumentException('请求缺少参数~');
                }
            }
            MingDaoYun::$filters[] = Filter::filterTypeCreate($map, $condition, $value);
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
     * @return int|mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function rowsCount()
    {
        MingDaoYun::$getParams['pageSize'] = 1;
        $result =  $this->getList();
        return $result['data']['total'] ?: 0;
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
            $data['data']['rows'] = $this->fetch($total, $data['data']['rows'], $count);
        }
        return $data;
    }

    /**
     * Notes:
     * User: Lany
     * DateTime: 2022/1/6 1:37 下午
     * @param $total
     * @param $rows
     * @param $count
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     * @return array
     */
    public function fetch($total, $rows, $count)
    {
        $flag = ceil($total/$count);
        $total -=  $count;
        for ($i = 2; $i <= $flag; $i ++) {

            if ($total > $count) {
                $total -= $count;
            }

            if ($i == $flag) {
                MingDaoYun::$getParams['pageSize'] = $total;
                static::$isClearParams = true;
            }
            MingDaoYun::$getParams['pageIndex'] = $i;
            $result = $this->getList();
            $rows = array_merge($rows, $result['data']['rows']);
        }
        return $rows;
    }
}