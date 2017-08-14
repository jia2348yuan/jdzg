<?php
//【工管用户后台】路由定义文件

return [

    //获取当前用户的项目列表
    'em/project' => 'em/project/index',
    //创建项目
    'em/project/create' => 'em/project/create',
    //删除项目
    'em/project/delete' => 'em/project/delete',
    //批量删除项目
    'em/project/deletes' => 'em/project/deletes',


    //根据手机号获取上级管理员信息
    'em/project/getDataByPhoneNumber' => ['em/project/getDataByPhoneNumber',['method' => 'POST']],

    //添加团队成员
    'em/team/add' =>  'em/team/add',







];
