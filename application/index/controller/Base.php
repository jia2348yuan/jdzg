<?php
/**
 * Description: 基础类，无需验证权限。
 * Author: zxd
 * DateTime: 2017/7/21 15:04
 */

namespace app\index\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
    public $param;

    public function _initialize()
    {
        parent::_initialize();
        /*防止跨域，部署环境应该启用*/
        /*
        header('Access-Control-Allow-Origin: '.\Think\Config::get('http_origin');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId");
        */

//        $param =  Request::instance()->param();
//        $this->param = $param;
    }

    //用户注册
    public function register()
    {
        if (request()->isPost()) {
            $userModel = model('User');
            $phone_number = input('phone_number');  //手机账号
            $password = input('password');  //账号密码
            $confirm = input('confirm');  //确认密码
            $role = input('role');  //用户角色
            $code = input('code');  //短信验证码
            $data = $userModel->register($phone_number, $password, $confirm, $role, $code);
            if (!$data) {
                return resultArray(['error' => $userModel->getError()]);
            }
            return resultArray(['data' => '注册成功']);
        } else {
            $this->error('非法操作！');
        }
    }

    //用户登录
    public function login(Request $request)
    {

        //return 'hello';

            $userModel = model('User');
            $phoneNumber = input('phone_number');  //手机账号

            $password = input('password');  //账号密码
            $ip = $request->ip();  //用户登录IP

            $data = $userModel->login($phoneNumber, $password, $ip);

            if (!$data) {
                return resultArray(['error' => $userModel->getError()]);
            }
            return resultArray(['data' => $data]);

    }

    //登出系统
    public function logout()
    {
        session('user', null);
        return resultArray(['data' => '登出系统成功！']);
    }

    //发送手机号获取验证码
    public function sendSMS()
    {
        if (request()->isPost()) {

            header("Content-Type: text/html; charset=UTF-8");
            $phoneNumber = input('phone_number');  //手机账号
            $checkData = [
                'phone_number' => $phoneNumber
            ];
            //自动验证
            $validate = validate('TempPhone');
            //验证不通过
            if (!$validate->check($checkData)) {
                return resultArray(['error' => $validate->getError()]);
            }
            //验证通过
            //创瑞短信模板
            $flag = 0;
            $params = '';//要post的数据
            $code = rand(123456, 999999);//获取随机验证码
            //以下信息自己填以下
            $argv = array(
                'name' => \Think\Config::get('sms_name'),     //必填参数。用户账号
                'pwd' => \Think\Config::get('sms_pwd'),         //必填参数。（web平台：基本资料中的接口密码）
                'content' => '短信验证码为：' . $code . '，请勿将验证码提供给他人。',   //必填参数。发送内容（1-500 个汉字）UTF-8编码
                'mobile' => $phoneNumber,           //必填参数。手机号码。多个以英文逗号隔开
                'stime' => \Think\Config::get('sms_stime'),   //可选参数。发送时间，填写时已填写的时间发送，不填时为当前时间发送
                'sign' => \Think\Config::get('sms_sign'),     //必填参数。用户签名。
                'type' => 'pt',               //必填参数。固定值 pt
                'extno' => \Think\Config::get('sms_extno')    //可选参数，扩展码，用户定义扩展码，只能为数字
            );
            //print_r($argv);exit;
            //构造要post的字符串
            //echo $argv['content'];
            foreach ($argv as $key => $value) {
                if ($flag != 0) {
                    $params .= "&";
                    $flag = 1;
                }
                $params .= $key . "=";
                $params .= urlencode($value);// urlencode($value);
                $flag = 1;
            }
            $url = "http://web.cr6868.com/asmx/smsservice.aspx?" . $params; //提交的url地址
            $con = substr(file_get_contents($url), 0, 1);  //获取信息发送后的状态

            if ($con == '0') {
                //写入手机号及验证码前先查找此手机号是否已经存在
                $TempPhoneModel = model('TempPhone');
                $data = $TempPhoneModel->getDataByPhoneNumber($phoneNumber);
                if ($data) {
                    $TempPhoneModel->updateData($phoneNumber, $code);
                } else {
                    $TempPhoneModel->createData($phoneNumber, $code);
                }
                return resultArray(['data' => '发送成功']);
            } else {
                return resultArray(['error' => '发送失败']);
            }
        } else {
            $this->error('非法操作！');
        }
    }

    //获取当前登录者信息
    public function getLoginInfo() {
        $loginInfo = session('user');
        if (!session('user')) {
            return resultArray(['error' => '请先登录']);
        }
        return resultArray(['data' => $loginInfo]);
    }
}