<?php
/**
 * Description:
 * Author: zxd
 * DateTime: 2017/8/7 13:57
 */

namespace app\em\validate;

use think\Validate;
class Team  extends Validate
{
    protected $rule = array(
        't_name'  =>  'require|max:50',
       //'t_phone'=> ['regex', 'length:11', 'regex:/^1[34578]{1}\d{9}$/'],
        ['t_phone',['require','/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$/'], '请输入手机账号|请输入正确的手机号'],

       [ 't_email' , ['require','/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/'],'请输入正确的邮箱|请输入正确的邮箱'],
        );

    //错误提示描述
    protected $message = array(
        't_name.require' => '请填输入组员名称',
        't_phone.require' => '手机号格式不正确',
        't_email.require' => '邮箱格式不正确',
    );

    //验证场景
    protected $scene = [
        'add'  =>  ['t_name','t_phone','t_email'],  //添加组员验证
        'phone' => ['t_phone'],  //根据手机号获取组员信息验证
        'join' =>['t_name','t_phone','t_email'],
    ];



}