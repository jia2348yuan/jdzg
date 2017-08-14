<?php
/**
 * Description: 默认访问类
 * Author: zxd
 * DateTime: 2017/7/21 15:06
 */

namespace app\index\controller;


class Index
{
    public function index()
    {
//        header('Access-Control-Allow-Origin:*');
//        // 响应类型
//        header('Access-Control-Allow-Methods:*');
//        // 响应头设置
//        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        return 'hello';
    }



    public function hello()
    {
        return 'hello world';

    }

}