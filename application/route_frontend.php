<?php
//【前端】路由定义文件

return [

    //用户注册
    'index/base/register' => ['index/base/register',['method' => 'POST']],
    // 用户登录
    'index/base/login' => ['index/base/login', ['method' => 'POST']],
    //获取手机验证码
    'index/base/sendSMS' => ['index/base/sendSMS',['method' => 'POST']],
    //登出系统
    'index/base/logout' => ['index/base/logout',['method' => 'POST']],
    //根据手机号获取用户信息
    'index/user/getDataByPhoneNumber' => ['index/user/getDataByPhoneNumber',['method' => 'POST']],
    //获取当前登陆者的信息
    'index/base/getLoginInfo' => ['index/base/getLoginInfo',['method' => 'POST']],
    //当前用户申请加入团队
    'index/user/jointeam'   => ['index/user/jointeam',['method' => 'POST']],


    // 测试路由
    'test' => 'index/test/index',

    // MISS路由
    // '__miss__'  => 'index/miss',

];
