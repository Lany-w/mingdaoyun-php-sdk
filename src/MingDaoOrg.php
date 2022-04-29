<?php

namespace Lany\MingDaoYun;

use GuzzleHttp\Exception\GuzzleException;
use Lany\MingDaoYun\Exceptions\HttpException;
use Lany\MingDaoYun\Exceptions\InvalidArgumentException;

class MingDaoOrg
{

    // https://www.showdoc.com.cn/mingdao/15519621

    //明道APPKEY
    public static string $appKey;
    //明道 secretKey
    public static string $secretKey;
    //明道云私有部署域名
    public static string $host;
    // 组织编号
    public static string $projectId;

    private $params = [];

    private $method = 'GET';

    //获取下级成员道云账号Id url
    public static string $getSubordinateURL = '/v2/open/structure/GetSubordinateIds';

    //添加汇报关系 url
    public static string $addStructures = '/v2/open/structure/addStructures';

    //移除汇报关系 url
    public static string $removeStructures = '/v2/open/structure/removeStructures';

    //替换汇报关系成员 url
    public static string $replaceStructure = '/v2/open/structure/replaceStructure';


    public function __construct()
    {
    }

    /**
     * 基础参数初始化
     * @param string $appKey
     * @param string $secretKey
     * @param string $host
     * @return MingDaoOrg
     */
    public function init(string $appKey, string $secretKey, string $host, string $projectId): MingDaoOrg
    {
        self::$appKey = $appKey;
        self::$secretKey = $secretKey;
        self::$host = $host;
        self::$projectId = $projectId;
        return $this;
    }


    private function getSignature($timestamps)
    {
        $tempdata = array(
            "AppKey" => self::$appKey,
            "SecretKey" => self::$secretKey,
            "Timestamp" => strval($timestamps)
        );
        $signstr = '';
        $keys = array_keys($tempdata);
        sort($keys);
        foreach ($keys as $value) {
            $signstr = $signstr . '&' . $value . '=' . $tempdata[$value];
        }
        $signstr = substr($signstr, 1);
        return base64_encode(hash("sha256", $signstr));
    }

    function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 参数检查
     * @return void
     * @throws InvalidArgumentException
     */
    public function checkAppInit()
    {
        if (!MingDaoOrg::$appKey || !MingDaoOrg::$secretKey || !MingDaoOrg::$host || !MingDaoOrg::$projectId) {
            throw new InvalidArgumentException('init error:请设置appkey,secretKey,host,projectId');
        }
    }


    /**
     * 执行查询
     * @param string $uri
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function exec(string $uri)
    {
        $timestamp = $this->getMillisecond();
        $this->checkAppInit();
        $params = [
            'timestamp' => $timestamp,
            'projectId' => self::$projectId,
            'appKey' => self::$appKey,
            'sign' => self::getSignature($timestamp)
        ];

        $url = self::$host . $uri;
        if ($this->method === 'GET') {
            $response = Http::client()->request($this->method, $url . '?' . http_build_query(array_merge($params, $this->params)));
        } else {
            $response = Http::client()->request($this->method, $url, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => array_merge($params, $this->params)
            ]);
        }


        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getBody(), $response->getStatusCode());
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 获取下级成员道云账号Id
     * https://www.showdoc.com.cn/mingdao/7372249331332438
     * @param string $superiorId 上级明道云账号Id，不传则代表只取顶级明道云账号Id
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getMySubordinate(string $superiorId = '')
    {
        $this->params['superiorId'] = $superiorId;
        return $this->exec(self::$getSubordinateURL);
    }

    /**
     * 添加汇报关系
     * @param string $superior 上级的邮箱或手机号，不传则代表添加到顶级
     * @param array $subordinates 下级成员的邮箱或手机号集合
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function addStructures(string $superior, array $subordinates)
    {
        $this->params['superior'] = $superior;
        $this->params['subordinates'] = $subordinates;
        $this->method = "POST";
        return $this->exec(self::$addStructures);
    }

    /**
     * 移除汇报关系
     * @param array $subordinates 需要移除的成员邮箱或手机号集合
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function removeStructures(array $subordinates)
    {
        $this->params['subordinates'] = $subordinates;
        $this->method = "POST";
        return $this->exec(self::$removeStructures);
    }


    /**
     * 替换汇报关系成员
     * @param string $preSubordinate 原成员的邮箱或手机号
     * @param string $newSubordinate 新成员的邮箱或手机号
     * @return mixed
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function replaceStructure(string $preSubordinate, string $newSubordinate)
    {
        $this->params['preSubordinate'] = $preSubordinate;
        $this->params['subordinates'] = $newSubordinate;
        $this->method = "POST";
        return $this->exec(self::$replaceStructure);
    }

}
