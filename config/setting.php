<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 七牛管理
    |--------------------------------------------------------------------------
    */
    // 七牛
    'qiniu_access_key' => 'c_M1yo7k90djYAgDst93NM3hLOz1XqYIKYhaNJZ4', // 七牛访问KEY
    'qiniu_secret_key' => 'Gb2K_HZbepbu-A45y646sP1NNZF3AqzY_w680d5h', // 七牛访问秘钥

    // 开发 七牛存储空间
    'qiniu_bucket' => env('QINIU_BUCKET', 'louwang-test'),
    'qiniu_url' => env('QINIU_URL', 'http://osibaji20.bkt.clouddn.com/'),// 七牛访问url
    // 七牛测试后缀
    'qiniu_suffix' => '-test',
    //微信请求登录
    'login_url' => env('LOGIN_URL'),
    //微信
    'wechat_url' => env('WECHAT_URL'),
    //短信验证码失效时间（redis存储 单位秒）// 2分钟
    'sms_life_time' => 60 * 2,
    // 房源默认图
    'pc_building_house_default_img' => 'https://upload.chulouwang.com/sass/admin/building_house_default.jpg',
    // 用户默认图
    'user_default_img' => 'https://upload.chulouwang.com/agent/default-header.png'
];