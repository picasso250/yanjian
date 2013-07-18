<?php

class WeiChatAuth
{
    /**
     * 获取access_token
     * 
     * 一定通过网络获取
     */
    public static function getAccessToken($appid, $appsecret)
    {
        $params = array(
            'grant_type' => 'client_credential',
            'appid' => $appid,
            'secret' => $appsecret,
        );
        $query = http_build_query($params);
        $url = 'https://api.weixin.qq.com/cgi-bin/token?'.$query;
        $return = file_get_contents($url);
        $ret = json_decode($return);
        if (is_object($ret) && isset($ret->access_token)) {
            return $ret->access_token;
        } elseif (is_object($ret)) { // 返回错误信息 errcode=>x, errmsg=>x,
            return $ret;
        }
        return false;
    }
}

class WeiChatMenu
{
    private $access_token;
    private $url = 'https://api.weixin.qq.com/cgi-bin/menu/'

    /**
     * 设置access_token
     * 
     * 在调用本类的任何方法之前，都必须调用这个方法先
     * 只需要调用一次
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * 获取菜单结构
     */
    public function get()
    {
        if (empty($this->access_token)) {
            return false;
        }
        $url = $this->url.'get?access_token='.$this->access_token;
        $return = file_get_contents($url);
        $ret = json_encode($return);
        return $ret;
    }

    /**
     * 删除菜单
     * 
     * 注意，此方法将删除所有的菜单，慎用！
     * 
     * @return 成功返回true，失败返回失败结构体
     */
    public function delete()
    {
        if (empty($this->access_token)) {
            return false;
        }
        $url = $this->url.'delete?access_token='.$this->access_token;
        $return = file_get_contents($url);
        $ret = json_encode($return);
        if (is_object($ret) && isset($ret->errcode) && $ret->errcode == 0) {
            return true;
        }
        return $ret;
    }

    /**
     * 创建菜单
     * 
     * 此方法尚且不明调用方式，待测试
     * 
     * @return 成功返回true，失败返回失败结构体
     */
    public function create($data)
    {
        $ch = curl_init($this->url.'create?access_token='.$this->access_token);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $return = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = new stdClass;
            $error->errcode = curl_errno($ch);
            $error->errmsg = curl_error($ch);
            return $error;
        }
        $ret = json_encode($return);
        if (is_object($ret) && isset($ret->errcode) && $ret->errcode == 0) {
            return true;
        }
        return $ret;
    }
}


