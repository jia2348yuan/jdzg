<?php
/**
 * Description: 短信验证码
 * Author: zxd
 * DateTime: 2017/7/21 15:07
 */
namespace app\index\model;

use think\Model;

class TempPhone extends Model
{
    /**
     * 根据手机号获取对应数据
     * @author zxd
     * @param string $phoneNumber   手机号
     * @return bool|static
     */
    public function getDataByPhoneNumber($phoneNumber = '')
    {
        $map['phone_number'] = $phoneNumber;
        $data = $this->where($map)->find();
        return $data;
    }


    /**
     * 手机号不存在的情况下，添加手机号到验证码表单
     * @author zxd
     * @param $phoneNumber 手机号码
     * @param $code   验证码
     * @return bool  返回布尔值
     */
    public function createData($phoneNumber, $code)
    {
        $addData = array(
            'phone_number'      =>  $phoneNumber,
            'code'       =>  $code,
            'deadline'   =>  time() + 60*60
        );
        $this->data($addData)->allowField(true)->save();
        return true;
    }


    /**
     * 手机号已经存在的情况下，只需要更新验证码
     * @author zxd
     * @param $phoneNumber  手机号
     * @param $verify   验证码
     * @return bool   布尔值
     */
    public function updateData($phoneNumber, $verify) {
        $updateData = array(
            'phone_number'      =>  $phoneNumber,
            'code'       =>  $verify,
            'deadline'   =>  time() + 60*60
        );
        $map['phone_number'] = $phoneNumber;
        $this->where($map)->update($updateData);
        return true;
    }


    /**
     * 验证手机短信
     * @author zxd
     * @param $phoneNumber  手机号
     * @param $code   验证码
     * @return bool  布尔值
     */
    public function checkCode($phoneNumber, $code) {
        $tempPhoneInfo = $this->getDataByPhoneNumber($phoneNumber);
        if ($tempPhoneInfo) {
            if ($tempPhoneInfo['code'] == $code) {
                $time = time();
                if ($time > $tempPhoneInfo['deadline']) {
                    $this->error = '此验证码已经失效';
                    return false;
                } else {
                    //短信验证通过
                    return true;
                }
            } else {
                $this->error = '验证码错误';
                return false;
            }
        } else {
            $this->error = '请发送手机号获取验证码';
            return false;
        }
    }
}