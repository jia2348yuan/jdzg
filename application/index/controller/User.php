<?php
/**
 * Description: 用户控制器
 * Author: zxd
 * DateTime: 2017/7/26 16:33
 */

namespace app\index\controller;

use think\Controller;

class User extends Controller
{
    //根据手机号获取用户信息（我的资料）
    public function getDataByPhoneNumber() {
       // echo 1111111;exit;
        $userModel = model('User');
        $phoneNumber = input('phone_number');  //手机账号
        $data = $userModel->getDataByPhoneNumber($phoneNumber);
       // var_dump($data);exit;
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        }
        return resultArray(['data' => $data]);
        var_dump(resultArray(['data' => $data]));exit;
    }

    //当前用户申请加入团队
    public function joinTeam(){

        $userModel = model('User');
        $realname = input('real_name');
        $phoneNumber = input('phone_number');
        $email = input('email');
        $code = input('join_code');

        $data = $userModel -> checkData($realname,$phoneNumber,$email,$code);
           // var_dump($data);exit;
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        }
        return resultArray(['data' => '成功加入团队']);


    }
}