<?php

namespace App\Services;

use GuzzleHttp\Client;

class LoginsService
{
    private $key = 'chulouwang';
    private $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+';

    //电话、密码获取token
    public function getToken($username, $password)
    {
        $data = [
            'username' => $username,
            'password' => $password,
            'scope' => config('passport.default.scope'),
            'client_id' => config('passport.default.client_id'),
            'client_secret' => config('passport.default.client_secret')
        ];
        $http = new Client();
        $result = null;
        $data['grant_type'] = 'password';
        try {
            $result = $http->post(url('/oauth/token'), [
                'form_params' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('申请密码令牌失败：字段信息为：' . json_encode($data) . '错误：' . $e->getMessage());
            $error = explode("\n", $e->getMessage())[1];
            if ($error[strlen($error) - 1] != '}') {
                $error = $error . '"}';
            }
            if (empty(json_decode($error))) return ['success' => false, 'message' => '服务器异常，请联系管理员'];

            switch (json_decode($error)->message) {
                case 'The user credentials were incorrect.':
                    $resultData = '用户名或密码错误！';
                    break;
                case 'Client authentication failed':
                case 'The requested scope is invalid, unknown, or malformed':
                    $resultData = '客户端出错，请重新下载！';
                    break;
                default:
                    $resultData = '未知错误，请联系管理员！';
                    break;
            }
            return ['success' => false, 'message' => $resultData];
        }
        return ['success' => true, 'message' => '获取成功', 'data' => [
            'token' => json_decode((string)$result->getBody(), true)['access_token']
        ]];
    }

    //openid获取token
    public function wechatLogins($user)
    {
        $token = $user->createToken($user->openid)->accessToken;
        if (empty($token)) return ['status' => false, 'message' => '获取令牌失败'];
        return ['status' => true, 'token' => $token];
    }


    //加密
    function lock($txt)
    {
        $nh = rand(0,64);
        $ch = $this->chars[$nh];
        $mdKey = md5($this->key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = base64_encode($txt);
        $tmp = '';
        $k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh+strpos($this->chars,$txt[$i])+ord($mdKey[$k++]))%64;
            $tmp .= $this->chars[$j];
        }
        return urlencode($ch.$tmp);
    }

    //解密
    function unlock($txt)
    {
        $txt = urldecode($txt);
        $ch = $txt[0];
        $nh = strpos($this->chars,$ch);
        $mdKey = md5($this->key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = substr($txt,1);
        $tmp = '';
        $k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos($this->chars,$txt[$i])-$nh - ord($mdKey[$k++]);
            while ($j<0) $j+=64;
            $tmp .= $this->chars[$j];
        }
        return base64_decode($tmp);
    }

    //密文获取电话
    public function getTel($saftySign)
    {
        $str = $this->unlock($saftySign);
        $arr = explode('-', $str);
        $tel = end($arr);
        return $tel;
    }

}