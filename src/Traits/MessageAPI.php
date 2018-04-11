<?php

namespace Singiu\Netease\Traits;

use Singiu\Http\Request;

/**
 * 网易云通信消息功能 API。
 *
 * Trait MessageAPI
 * @package Singiu\Netease\Traits
 * @property-read Request $_http
 */
trait MessageAPI
{
    /**
     * 发送普通消息。
     *
     * @param string $from 发送者 accid，最大 32 字符
     * @param string $to 接收者 accid
     * @param string $text 发送的消息
     * @return mixed
     * @throws
     */
    public function sendTextMessage($from, $to, $text)
    {
        $response = $this->_action('msg/sendMsg.action', [
            'from' => $from,
            'ope' => 0,
            'to' => $to,
            'type' => 0,
            'body' => json_encode(['msg' => $text], JSON_UNESCAPED_UNICODE)
        ]);
        return $this->_getResult($response);
    }

    /**
     * 发送自定义系统通知，类似消息透传。
     *
     * @param string $from
     * @param string $to
     * @param array $attach 要推送的自定义消息体，客户端接收到的是 JSON 格式的数据
     * @return mixed
     * @throws \Exception
     */
    public function sendAttachMessage($from, $to, $attach)
    {
        $response = $this->_action('msg/sendAttachMsg.action', [
            'from' => $from,
            'msgtype' => 0,
            'to' => $to,
            'attach' => json_encode($attach, JSON_UNESCAPED_UNICODE)
        ]);
        return $this->_getResult($response);
    }
}