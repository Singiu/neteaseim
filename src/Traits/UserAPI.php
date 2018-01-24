<?php

namespace Singiu\Netease\Traits;

/**
 * 网易云通信ID及用户相关的API操作。
 *
 * @property-read \Singiu\Netease\Http\Request http;
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
        $response = $this->http->post('user/create.action', [
            'headers' => $this->getHttpHeaders(),
            'data' => $postData
        ]);
        return $this->getResult($response, 'info');
    }

    /**
     * 更新云通信ID信息。
     *
     * @param $accId
     * @param array $data 只支持 prop（自定义数据JSON） 和 token（类似密码）这两个字段。
     * @return mixed
     * @throws \Exception
     */
    public function update($accId, Array $data)
    {
        $data['accid'] = $accId;
        $response = $this->http->post('user/update.action', [
            'headers' => $this->getHttpHeaders(),
            'data' => $data
        ]);
        return $this->getResult($response);
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
        $response = $this->http->post('user/refreshToken.action', [
            'headers' => $this->getHttpHeaders(),
            'data' => ['accid' => $accId]
        ]);
        return $this->getResult($response, 'info');
    }

    /**
     * 禁用网易云通信ID。
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
        $reponse = $this->http->post('user/block.action', [
            'headers' => $this->getHttpHeaders(),
            'data' => $data
        ]);
        return $this->getResult($reponse);
    }

    /**
     * 解禁网易云通信ID。
     *
     * @param $accId
     * @throws \Exception
     */
    public function unblock($accId)
    {
        $response = $this->http->post('user/unblock.action', [
            'headers' => $this->getHttpHeaders(),
            'data' => ['accid' => $accId]
        ]);
        $this->getResult($response);
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
        if (is_array($userInfos) && count($userInfos) > 0)
            return $userInfos[0];
        else return $userInfos;
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
        $response = $this->http->post('user/getUinfos.action', [
            'headers' => $this->getHttpHeaders(),
            'data' => ['accids' => json_encode($accIds)]
        ]);
        return $this->getResult($response, 'uinfos');
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
        $response = $this->http->post('user/updateUinfo.action', [
            'headers' => $this->getHttpHeaders(),
            'data' => $data
        ]);
        return $this->getResult($response);
    }
}