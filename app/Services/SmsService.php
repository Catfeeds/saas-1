<?php
/**
 * 短信接口服务层
 */
namespace App\Services;

use App\Handler\Common;

class SmsService{

    //发送短信的接口地址
    protected static $apiSendUrl = null;

    //发送变量模板
    protected  static $apiSendVariableUrl = null;

    //查询余额的接口地址
    protected static $apiBalanceQueryUrl = null;

    //短信帐号从 https://zz.253.com/site/login.html 里面获取。
    protected static $apiAccount = null;

    //短信密码从 from https://zz.253.com/site/login.html 里面获取。
    protected static $apiPassword = null;

    /*
     * 初始化
     * @author
     */
    public function __construct()
    {
        self::$apiAccount = config('sms.set.account');
        self::$apiPassword = config('sms.set.password');
        self::$apiSendUrl = config('sms.set.send_url');
        self::$apiSendVariableUrl = config('sms.ste.send_variable_url');
        self::$apiBalanceQueryUrl = config('sms.set.balance_query_url');
    }

    /**
     * 发送短信需要的接口参数
     *
     * @param string $mobile 		手机号码
     * @param string $msg 			想要发送的短信内容
     * @param integer $needstatus 	是否需要状态报告 '1'为需要 '0'位不需要。
     * @return array
     */
    public function sendSMS( $mobile, $msg, $needstatus = true)
    {
        //发送短信的接口参数
        $postArr = array (
            'account'  =>  self::$apiAccount,
            'password' => self::$apiPassword,
            'msg' => urlencode($msg),
            'phone' => $mobile,
            'report' => $needstatus
        );
        // 手机号有效验证
        if (!Common::isMobile($mobile)) return ['status' => false, 'message' => '请使用有效手机号码'];
        $result = $this->curlPost(self::$apiSendUrl, $postArr);
        if(!is_null(json_decode($result))){
            $output=json_decode($result,true);
            if(isset($output['code'])  && $output['code']=='0'){
                return ['status' => true, 'message' => '短信发送成功'];
            }else{
                return ['status' => false, 'message' => $output['errorMsg']];
            }
        }else{
            return ['status' => false, 'message' => $result];
        }
    }

    //发送模板变量短信
    public function sendVariableSMS($msg, $params)
    {
        $postArr = array (
            'account' => self::$apiAccount,
            'password' => self::$apiPassword,
            'msg' => $msg,
            'params' => $params,
            'report' => 'true'
        );
        $result = $this->curlPost( self::$apiSendVariableUrl, $postArr);
        if(!is_null(json_decode($result))){
            $output=json_decode($result,true);
            if(isset($output['code'])  && $output['code']=='0'){
                return ['status' => true, 'message' => '短信发送成功'];
            }else{
                return ['status' => false, 'message' => $output['errorMsg']];
            }
        }else{
            return ['status' => false, 'message' => $result];
        }
    }

    /**
     * 查询余额
     */
    public function queryBalance()
    {
        // 查询接口参数
        $postArr = array (
            'account' => self::$apiAccount,
            'password' => self::$apiPassword,
        );
        $result = $this->curlPost(self::$apiBalanceQueryUrl, $postArr);
        return $result;
    }



    /**
     * 说明：post请求
     *
     * @param $url
     * @param $postFields
     * @return mixed|string
     * @author
     */
    private function curlPost($url,$postFields){
        $postFields = json_encode($postFields);
        $ch = curl_init ();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8'
            )
        );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt( $ch, CURLOPT_TIMEOUT,1);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec ( $ch );
        if (false == $ret) {
            $result = curl_error(  $ch);
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "请求状态 ". $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
        curl_close ( $ch );
        return $result;
    }

    /**
     * 魔术获取
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * 魔术设置
     */
    public function __set($name,$value)
    {
        $this->$name=$value;
    }
}