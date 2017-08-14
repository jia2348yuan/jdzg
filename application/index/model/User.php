<?php
/**
 * Description: 系统用户
 * Author: zxd
 * DateTime: 2017/7/21 15:09
 */

namespace app\index\model;

use think\Model;

class User extends Model
{
    //自动写入时间戳
    protected $createTime = 'create_time';
    protected $updateTime = false;
    protected $autoWriteTimestamp = true;

    //自动完成
    protected $auto = [];
    protected $insert = [
        'password',
        'status' => 1
    ];

    //密码加密
    public function setPasswordAttr($value)
    {
        return user_md5($value);
    }

    /**
     * 用户登录
     * @author zxd
     * @param $phoneNumber  手机号码
     * @param $password   密码
     * @return bool  返回布尔值
     */
    public function login($phoneNumber, $password, $ip)
    {
        $checkData = [
            'phone_number'  => $phoneNumber,
            'password'  =>  $password
        ];
        //自动验证
        $validate = validate('User');
        //自动验证不通过
        if (!$validate->scene('login')->check($checkData)) {
            $this->error = $validate->getError();
            return false;
        }

        $map['phone_number'] = $phoneNumber;
        $userInfo = $this->where($map)->find();
        if (!$userInfo) {
            $this->error = '帐号不存在';
            return false;
        }
        if (user_md5($password) !== $userInfo['password']) {
            $this->error = '密码错误';
            return false;
        }
        //冻结禁用这个功能暂时没用，为后期考虑
        if ($userInfo['status'] === 0) {
            $this->error = '帐号已被禁用';
            return false;
        }
        // 所有信息验证成功，保存登录信息，更新最后登录信息，返回用户信息，前台处理处理后调转页面
        $time = getTime();
        //var_dump($time);exit;


        //通过session保存用户登录信息
            session('user', array(
                'id'                  =>   $userInfo['id'],
                'phone_number'       =>   $userInfo['phone_number'],
                'realname'           =>   $userInfo['realname'],
                'role'                =>   $userInfo['role'],
                'last_login_time'    =>   $time,
                'last_login_ip'      =>   $ip
            ));
//
//            logger('保存user：'.session('user')['id']);
//            logger('账号：'.session('user')['phone_number']);
//            logger('角色：'.session('user')['role']);
//            logger('最后登录时间：'.session('user')['last_login_time']);
//            logger('最后登录IP：'.session('user')['last_login_ip']);

        //通过缓存机制保存用户登录信息
//        session_start();
//        $info['userInfo'] = $userInfo;
//        $info['sessionId'] = session_id();
//        $authKey = user_md5($userInfo['phone_number'].$userInfo['password'].$info['sessionId']);
//        $info['authKey'] = $authKey;
//        cache('Auth_'.$authKey, null);
//        cache('Auth_'.$authKey, $info, config('login_session_valid'));


        $updateData = array(
            'id'                =>  $userInfo['id'],
            'last_login_time'   =>  $time,
            'last_login_ip'     =>  $ip,
            'login_count'       =>  array('exp', 'login_count+1')
        );
        $this->update($updateData);

        // 返回信息
        //$data['authKey']		= $authKey;
        //$data['sessionId']		= $info['sessionId'];
        $data['userInfo']		= $userInfo;
        return $data;

       // var_dump($data);exit;
    }

    /**
     * 用户注册
     * @author zxd
     * @param $phoneNumber 手机号码
     * @param $password 密码
     * @param $confirm 确认密码
     * @param $code 验证码
     * @return bool
     */
    //用户注册 不需要选择角色$role
    public function register($phoneNumber, $password, $confirm, $role, $code)
    {
        //dump(33);exit;
        $addData = [
            'phone_number'  => $phoneNumber,
            'password'  =>  $password,
            'confirm'  =>  $confirm,
            'role'  =>  $role,
            'code'  =>  $code
        ];
       //dump($addData);exit;
        //自动验证
        $validate = validate('User');
        //自动验证不通过
        if (!$validate->scene('add')->check($addData)) {
            $this->error = $validate->getError();
            return false;
        }

        //验证短信验证码
        $TempPhoneModel = model('TempPhone');
        $checkResult = $TempPhoneModel->checkCode($phoneNumber, $code);
        if ($checkResult) {
            //$this->strict(false)->insert($addData);
            $this->data($addData)->allowField(true)->save();
            return true;
        } else {
            $this->error = $TempPhoneModel->getError();
            return false;
        }
    }

    /**
     * 根据手机号获取用户信息
     * @author zxd
     * @param $phoneNumber 手机号
     * @return array|bool|false|\PDOStatement|string|Model
     */
    public function getDataByPhoneNumber($phoneNumber) {
        $checkData = [
            'phone_number'  => $phoneNumber
        ];
        //自动验证
        $validate = validate('User');
        //自动验证不通过
        if (!$validate->scene('phone')->check($checkData)) {
            $this->error = $validate->getError();
            return false;
        }
        $map['phone_number'] = $phoneNumber;
        $data = $this->where($map)->find();
        if (!$data) {
            $this->error = '此账号不存在';
            return false;
        }
        return $data;
    }
    /**
     * 加入团队
     * @author zxd
     * @param real_name 成员姓名
     * @param phone_number 成员电话
     * @param email 电子邮箱
     * @param $code 验证码
     * @return bool
     */
    public function checkData($realname,$phoneNumber,$email,$code){
        $data = [
            'real_name' => $realname,
            'phone_number' => $phoneNumber,
            'email' => $email,
          'join_code' =>$code

        ];
        $validate = validate('User');
//var_dump($data);exit;
        if (!$validate->scene('join')->check($data)) {//验证出错
           // var_dump($validate);exit;
            $this->error = $validate->getError();

            return false;
        }

        //验证短信验证码
        /*$TempPhoneModel = model('TempPhone');
        $checkResult = $TempPhoneModel->checkCode($phoneNumber, $code);
        if ($checkResult) {
            $this->strict(false)->insert($data);
            $this->data($data)->allowField(true)->save();
            return true;
        } else {
            $this->error = $TempPhoneModel->getError();
            return false;
        }*/

       // Db::name('testt')->insertAll($data);

        return $data;
        //var_dump($data);exit;
    }


}