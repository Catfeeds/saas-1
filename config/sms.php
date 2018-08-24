<?php

return [
    // 短信发送开关（查看验证码。在redis中查看）
    'open' => env('SMS_OPEN'),
    /*
    |--------------------------------------------------------------------------
    | 短信配置
    |--------------------------------------------------------------------------
    |
    */
    'set' => [
        //发送固定模板短信
        'send_url' => 'http://smssh1.253.com/msg/send/json',
        //发送变量模板短信
        'send_variable_url' => 'http://smssh1.253.com/msg/variable/json',
        //查询余额
        'balance_query_url' => 'http://smssh1.253.com/msg/balance/json',
        'account' => 'N1091596',
        'password' => 'WwoTtAPEK9a9ce',
    ],

    'clw' => [
        'updatePwd' => '短信验证码：%s,您正在修改帐号登录密码,请输入验证码完成验证（%s分钟内有效）,请勿泄露短信验证码。',
        'register' => '动态验证码：%s,您正在注册帐号,请输入验证码完成手机号码验证（%s分钟内有效）。如非本人操作，请忽略此短信。'
    ],
];
