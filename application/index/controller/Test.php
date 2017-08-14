<?php
/**
 * Description: 测试类 部署环境删除
 * Author: zxd
 * DateTime: 2017/7/21 15:05
 */

namespace app\index\controller;

use think\Request;
use think\Session;

use think\Controller;
use app\index\model\Link as Links;  //引入空间类文件，并且取别名，因为当前类名也是Link


class Test extends Controller
{
    public function index(Request $request)
    {
        $ip = $request->ip();
        session('user', array(
            'id'                  =>   30,
            'phone_number'       =>   '18782917916',
            'realname'           =>   '周小冬',
            'role'                =>   1,
            'last_login_time'    =>   time(),
            'last_login_ip'      =>  $ip
        ));
        print_r(session('user'));
    }




}
