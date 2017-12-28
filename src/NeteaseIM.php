<?php

namespace Singiu\Netease;

use Singiu\Netease\Http\Request;
use Singiu\Netease\Http\Response;
use Singiu\Netease\Traits\UserAPI;

class NeteaseIM
{
    use UserAPI;
    protected $appKey;
    protected $appSecret;
    protected $http;

    /**
     * 构造函数。
     *
     * @param string $appKey
     * @param string $appSecret
     */
    public function __construct($appKey, $appSecret)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->http = new Request(['base_uri' => 'https://api.netease.im/nimserver']);
    }

    /**
     * 处理请求响应数据。
     *
     * @param $response
     * @param $key
     * @return null
     */
    protected function getResult(Response $response, $key = null)
    {
        $responseJson = $response->getResponseJson();
        return $key != null && $key != '' && array_key_exists($key, $responseJson)
            ? $responseJson[$key]
            : $responseJson;
    }

    /**
     * 处理请求返回的错误码对应的意义。
     *
     * @param $code
     * @return string
     */
    protected function getError($code)
    {
        $error_code = require(__DIR__ . '/ErrorCode.php');
        if (array_key_exists($code, $error_code)) {
            return $error_code[$code];
        }
        return '';
    }

    /**
     * 构建API请求头。
     *
     * @return array
     * @throws \Exception
     */
    protected function getHttpHeaders()
    {
        $curTime = time();
        $nonce = $this->str_random(32);
        $appSecret = $this->appSecret;
        $checkSum = sha1($appSecret . $nonce . $curTime);
        $header = [
            'AppKey' => $this->appKey,
            'Nonce' => $nonce,
            'CurTime' => $curTime,
            'CheckSum' => $checkSum,
            'Content-type' => 'application/x-www-form-urlencoded;charset=utf-8'
        ];
        return $header;
    }

    /**
     * laravel 的随机字串生成函数。
     *
     * @param int $length
     * @return string
     * @throws \Exception
     */
    protected function str_random($length = 16)
    {
        $string = '';
        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }
        return $string;
    }
}