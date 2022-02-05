<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2022/2/5 12:15 下午
 */

namespace Lany\MingDaoYun\Provider;

use Lany\MingDaoYun\MingDaoYun;

class ThinkPHPServiceProvider extends \think\Service
{
    public function register()
    {
        $class= (new MingDaoYun())->init(config('mingdaoyun.appKey'), config('mingdaoyun.sign'), config('mingdaoyun.host'));
        $this->app->bind('mdy', $class);
    }
}