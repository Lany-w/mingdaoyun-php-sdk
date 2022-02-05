<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2022/2/5 12:15 下午
 */

namespace Lany\MingDaoYun\Provider;

use Lany\MingDaoYun\MingDaoYun;
use think\facade\Config;

class ThinkPHPServiceProvider extends \think\Service
{
    /**
     * Notes:TP6新增的service,5.1没有
     * User: Lany
     * DateTime: 2022/2/5 4:37 下午
     */
    public function boot()
    {
        $class= (new MingDaoYun())->init(Config::get('mingdaoyun.appKey', ''), Config::get('mingdaoyun.sign', ''), Config::get('mingdaoyun.host', ''));
        $this->app->bind('mdy', $class);
    }
}