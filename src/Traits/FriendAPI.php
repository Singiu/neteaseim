<?php

namespace Singiu\Netease\Traits;

use Singiu\Http\Response;

/**
 * Trait FriendAPI
 * @package Singiu\Netease\Traits
 * @method Response _action($uri, $post_data)
 */
trait FriendAPI
{
    /**
     * 加好友。
     *
     * @param $accid    string  加好友发起者accid
     * @param $faccid   string  加好友接收者accid
     * @param $type     int     1直接加好友，2请求加好友，3同意加好友，4拒绝加好友
     * @param $message  string  加好友对应的请求消息，第三方组装，最长256字符
     * @return string
     */
    public function friendAdd($accid, $faccid, $type, $message)
    {
        $post_data = [
            'accid' => $accid,
            'faccid' => $faccid,
            'type' => $type,
            'message' => $message
        ];
        return $this->_action('friend/add.action', $post_data)->getResponseText();
    }

    /**
     * 删除好友。
     *
     * @param $accid    string  发起者accid
     * @param $faccid   string  要删除朋友的accid
     * @return mixed
     */
    public function friendDelete($accid, $faccid)
    {
        $post_data = [
            'accid' => $accid,
            'faccid' => $faccid
        ];
        return $this->_action('friend/delete.action', $post_data)->getResponseText();
    }

    /**
     * 获取好友关系。
     *
     * @param $accid        string  发起者accid
     * @param $updatetime   int     更新时间戳，接口返回该时间戳之后有更新的好友列表
     * @param $createtime   int     定义同updatetime
     * @return mixed
     */
    public function friendGet($accid, $updatetime, $createtime)
    {
        $post_data = [
            'accid' => $accid,
            'updatetime' => $updatetime,
            'createtime' => $createtime
        ];
        return $this->_action('friend/get.action', $post_data)->getResponseText();
    }

    /**
     * 更新好友相关信息。
     *
     * @param $accid    string  发起者accid
     * @param $faccid   string  要修改朋友的accid
     * @param $alias    string  给好友增加备注名，限制长度128
     * @param $ex       string  修改ex字段，限制长度256
     * @return mixed
     */
    public function friendUpdate($accid, $faccid, $alias, $ex)
    {
        $post_data = [
            'accid' => $accid,
            'faccid' => $faccid,
            'alias' => $alias,
            'ex' => $ex
        ];
        return $this->_action('friend/update.action', $post_data)->getResponseText();
    }

    /**
     * 查看指定用户的黑名单和静音列表。
     *
     * @param $accid string 发起者accid
     * @return mixed
     */
    public function userListBlackAndMuteList($accid)
    {
        $post_data = [
            'accid' => $accid
        ];
        return $this->_action('user/listBlackAndMuteList.action', $post_data)->getResponseText();
    }

    /**
     * 设置黑名单/静音。
     *
     * @param $accid        string  用户帐号，最大长度 32 字符，必须保证一个 APP 内唯一
     * @param $targetAcc    string  被加黑或加静音的帐号
     * @param $relationType int     本次操作的关系类型,1:黑名单操作，2:静音列表操作
     * @param $value        int     操作值，0:取消黑名单或静音，1:加入黑名单或静音
     * @return string
     */
    public function userSetSpacialRelation($accid, $targetAcc, $relationType, $value)
    {
        $post_data = [
            'accid' => $accid,
            'targetAcc' => $targetAcc,
            'relationType' => $relationType,
            'value' => $value
        ];
        return $this->_action('user/setSpecialRelation.action', $post_data)->getResponseText();
    }
}