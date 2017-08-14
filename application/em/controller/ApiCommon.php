<?php
/**
 * Description: 工管后台Api基础类，权限验证
 * Author: zxd
 * DateTime: 2017/7/25 14:55
 */

namespace app\em\controller;

use think\Controller;
use think\Request;

class ApiCommon extends Controller {
    public $param;
    public function _initialize()
    {
        /*用过header头判断是否登录*/
        /*
        //获取头部信息
        $header = Request::instance()->header();
        $authKey = $header['authkey'];
        $sessionId = $header['sessionid'];
        $cache = cache('Auth_'.$authKey);

        // 校验sessionid和authKey
        if (empty($sessionId)||empty($authKey)||empty($cache)) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(['code'=>101, 'error'=>'登录已失效']));
        }

        // 检查账号有效性
        $userInfo = $cache['userInfo'];
        $map['id'] = $userInfo['id'];
        $map['status'] = 1;
        if (!Db::name('admin_user')->where($map)->value('id')) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(['code'=>102, 'error'=>'账号已被删除或禁用']));
        }
        // 更新缓存
        cache('Auth_'.$authKey, $cache, config('login_session_valid'));
        $GLOBALS['userInfo'] = $userInfo;
        */

        /*通过session 判断是否登录*/
        /*
        if (!session('user'))
        {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(['code'=>101, 'error'=>'登录已失效']));
        }
        */

        $param =  Request::instance()->param();
        //var_dump($param);exit;
        $this->param = $param;
    }


}