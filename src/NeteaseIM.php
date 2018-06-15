<?php

namespace Singiu\Netease;

use Singiu\Http\Http;
use Singiu\Http\Request;
use Singiu\Http\Response;
use Singiu\Netease\Traits\FriendAPI;
use Singiu\Netease\Traits\MessageAPI;
use Singiu\Netease\Traits\UserAPI;

class NeteaseIM
{
    use UserAPI;
    use MessageAPI;
    use FriendAPI;

    protected $_appKey;
    protected $_appSecret;
    protected $_http;
    protected $_errorCode;

    /**
     * 构造函数。
     *
     * @param string $appKey
     * @param string $appSecret
     */
    public function __construct($appKey, $appSecret)
    {
        $this->_appKey = $appKey;
        $this->_appSecret = $appSecret;
        $this->_http = new Request('https://api.netease.im/nimserver');
        $this->_http->setHttpVersion(Http::HTTP_VERSION_1_1);
    }

    /**
     * 因为网易的接口都是使用 post 方法发送，所以这里做一个统一的发送方法。
     *
     * @param $uri
     * @param $data
     * @return Response
     * @throws \Exception
     */
    protected function _action($uri, $data)
    {
        $post_data = [
            'headers' => $this->_getHttpHeaders(),
            'data' => $data
        ];
        return $this->_http->post($uri, $post_data);
    }

    /**
     * 处理请求返回的错误码对应的意义。
     *
     * @param $code
     * @return string
     */
    protected function _getError($code)
    {
        if ($this->_errorCode == null) {
            $this->_errorCode = require(__DIR__ . '/ErrorCode.php');
        }
        if (array_key_exists($code, $this->_errorCode)) {
            return $this->_errorCode[$code];
        }
        return '';
    }

    /**
     * 构建API请求头。
     *
     * @return array
     * @throws \Exception
     */
    protected function _getHttpHeaders()
    {
        $curTime = time();
        $nonce = $this->_strRandom(32);
        $appSecret = $this->_appSecret;
        $checkSum = sha1($appSecret . $nonce . $curTime);
        $header = [
            'AppKey' => $this->_appKey,
            'Nonce' => $nonce,
            'CurTime' => $curTime,
            'CheckSum' => $checkSum,
            'Content-type' => 'application/x-www-form-urlencoded;charset=utf-8'
        ];
        return $header;
    }

    /**
     * 处理请求响应数据。
     *
     * @param $response
     * @param $key
     * @return null
     */
    protected function _getResult(Response $response, $key = null)
    {
        $responseArray = $response->getResponseArray();
        return $key != null && $key != '' && is_array($responseArray) && array_key_exists($key, $responseArray)
            ? $responseArray[$key]
            : $responseArray;
    }

    /**
     * laravel 的随机字串生成函数。
     *
     * @param int $length
     * @return string
     * @throws \Exception
     */
    protected function _strRandom($length = 16)
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