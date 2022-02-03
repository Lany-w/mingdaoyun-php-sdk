<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2022/2/3 9:30 下午
 */

namespace Lany\MingDaoYun\Provider;

use Lany\MingDaoYun\MingDaoYun;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(MingDaoYun::class, function(){
            return (new MingDaoYun)->init(config('mingdaoyun.appKey'), config('mingdaoyun.sign'), config('mingdaoyun.host'));
        });

        $this->app->alias(MingDaoYun::class, 'mdy');
    }

    public function provides()
    {
        return [MingDaoYun::class, 'mdy'];
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/mingdaoyun.php' => config_path('mingdaoyun.php'),
        ]);
    }
}