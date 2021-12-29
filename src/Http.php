<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2021/12/29 9:39 上午
 */

namespace Lany\MingDaoYun;

use GuzzleHttp\Client;

class Http
{
    protected $options;

    public function client()
    {
        return new Client($this->options);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->options = $options;
    }
}