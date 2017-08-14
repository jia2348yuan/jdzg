<?php
/**
 * Description: 用户信息验证
 * Author: zxd
 * DateTime: 2017/7/21 15:02
 */
namespace app\index\validate;

use think\Validate;

class TempPhone extends Validate
{

    //验证用户规则
    protected $rule = array(
        ['phone_number',['require','/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$/'], '请输入手机账号|请输入正确的手机号']
    );
    //错误提示描述
    protected $message = array(

    );
    //验证场景
    protected $scene = [

    ];
}