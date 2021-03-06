<?php
/**
 * redis key 配置
 * User: 郭庆
 * Date: 2017/6/13
 * Time: 下午3:38
 */

return [

    /*
    |--------------------------------------------------------------------------
    | 短信验证码
    |--------------------------------------------------------------------------
    | 作用：用于存储：给某一个用户发送的短信验证码
    |
    | KEY = :ACTION:ORDER:[业务]:[用户手机号]
    | VALUE = 100000-999999
    | @author 罗振
    */
    'STRING_SMSCODE_' => 'STRING:SMSCODE:',
];
