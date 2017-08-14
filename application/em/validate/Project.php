<?php
/**
 * Description: 创建项目自动验证
 * Author: zxd
 * DateTime: 2017/7/26 15:16
 */

namespace app\em\validate;

use think\Validate;

class Project extends Validate
{

    //验证规则
    protected $rule = array(
        'name'  =>  'require|max:50',
        ['number',['require','/^[A-Z]{6}[0-9]{2}$/'], '请输入项目编号|项目编号必须是6位大写字母+2位数字组合'],
        ['manager_phone_number',['require','/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$/'], '请输入手机账号|请输入正确的手机号'],
        'manager_realname' => 'require|max:30',
        ['superior_manager_phone_number',['require','/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$/'], '请输入上级管理手机账号|请输入正确的上级管理手机号'],
        ['class',['require','/^5|4|3$/'], '请选择项目级别|不存在的项目级别'],
    );

    //错误提示描述
    protected $message = array(
        'name.require' => '请填输入项目名称',
        'name.max' => '项目名称不得超过50位',
        'manager_realname.require' => '请输入管理员姓名',
        'manager_realname.max' => '管理员姓名不得超过30位',
    );

    //验证场景
    protected $scene = [
        'add'  =>  ['name','number','manager_phone_number','manager_realname', 'class','manager_id'],  //创建项目验证
        'phone' => ['superior_manager_phone_number'],  //根据手机号获取上级管理员信息验证
    ];
}