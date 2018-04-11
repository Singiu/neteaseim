<?php

namespace Singiu\Netease\Traits;

/**
 * 网易云通信ID及用户相关的API操作。
 *
 * @property-read \Singiu\Http\Request $_http;
 */
trait UserAPI
{
    /**
     * 创建网易云通信ID。
     *
     * @param $accId
     * @param array $postData
     * @return null
     * @throws \Exception
     */
    public function create($accId, Array $postData)
    {
        $postData['accid'] = $accId;
        $response = $this->_action('user/create.action', $postData);
        return $this->_getResult($response, 'info');
    }

    /**
     * 更新云通信 ID 信息。
     *
     * @param $accId
     * @param array $data 只支持 prop（自定义数据JSON） 和 token（类似密码）这两个字段。
     * @return mixed
     * @throws \Exception
     */
    public function update($accId, Array $data)
    {
        $data['accid'] = $accId;
        $response = $this->_action('user/update.action', $data);
        return $this->_getResult($response);
    }

    /**
     * 刷新并获取用户的 token（密码）。
     *
     * @param $accId
     * @return mixed
     * @throws \Exception
     */
    public function refreshToken($accId)
    {
        $response = $this->_action('user/refreshToken.action', ['accid' => $accId]);
        return $this->_getResult($response, 'info');
    }

    /**
     * 禁用网易云通信 ID。
     *
     * @param $accId
     * @param bool $needkick
     * @return mixed
     * @throws \Exception
     */
    public function block($accId, $needkick = false)
    {
        $data = [
            'accid' => $accId,
            'needkick' => $needkick ? 'true' : 'false'
        ];
        $reponse = $this->_action('user/block.action', $data);
        return $this->_getResult($reponse);
    }

    /**
     * 解禁网易云通信 ID。
     *
     * @param $accId
     * @throws \Exception
     */
    public function unblock($accId)
    {
        $response = $this->_action('user/unblock.action', ['accid' => $accId]);
        $this->_getResult($response);
    }

    /**
     * 获取用户信息。
     *
     * @param $accId
     * @return mixed
     * @throws \Exception
     */
    public function getUserInfo($accId)
    {
        $userInfos = $this->getUserInfos([$accId]);
        return is_array($userInfos) && count($userInfos) > 0 ? $userInfos[0] : $userInfos;
    }

    /**
     * 批量获取用户信息。
     *
     * @param array $accIds
     * @return mixed
     * @throws \Exception
     */
    public function getUserInfos(Array $accIds)
    {
        $response = $this->_action('user/getUinfos.action', ['accids' => json_encode($accIds, JSON_UNESCAPED_UNICODE)]);
        return $this->_getResult($response, 'uinfos');
    }

    /**
     * 更新用户的名片信息。
     *
     * @param $accId
     * @param array $data 支持的字段请参考网易官方文档
     * @return mixed
     * @throws \Exception
     */
    public function updateUserInfo($accId, array $data)
    {
        $data['accid'] = $accId;
        $fillable = ['accid', 'name', 'icon', 'sign', 'email', 'birth', 'mobile', 'gender', 'ex'];
        $data = array_intersect_key($data, array_flip($fillable));
        $response = $this->_action('user/updateUinfo.action', $data);
        return $this->_getResult($response);
    }
}