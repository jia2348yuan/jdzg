<?php
/**
 * Description: 用户信息验证
 * Author: zxd
 * DateTime: 2017/7/21 15:02
 */
namespace app\index\validate;

use think\Validate;

class User extends Validate
{

    //验证用户规则
    protected $rule = array(
        ['phone_number',['require','/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$/'], '请输入手机账号|请输入正确的手机号'],
        'password'  =>  'require',
        'confirm'  => 'require|confirm:password',
        ['role',['require','/^[1|2|3]$/'], '请选择用户角色|没有此角色'],
        'code'  =>  'require',
        'join_code'  =>  '1234',
        [ 'email' , ['require','/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/'],'请输入正确的邮箱|请输入正确的邮箱'],

    );
    //错误提示描述
    protected $message = array(
        'phone_number' => '此账号已被注册',
        'password.require' => '请输入账号密码',
        'confirm.require' => '请输入确认密码',
        'confirm.confirm' => '确认密码必须和密码一致',
        'role.require' => '用户角色必须选择',
        'code.require' => '短信验证码必须填写',
        'email' => '电子邮箱必须填',
       'join_code' =>'验证码为1234',
    );
    //验证场景
    protected $scene = [
        'phone' => ['phone_number'],  //根据手机号获取用户信息验证
        'login'  =>  ['phone_number','password'],  //登录验证
        'add'  =>  ['phone_number'=>['require','/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$/','unique:user'],'password','confirm','role','code'],  //注册验证
        'join'=>['phone_number','email','join_code'],
    ];
}