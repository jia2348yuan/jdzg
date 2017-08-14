<?php
/**
 * Description:
 * Author: zxd
 * DateTime: 2017/8/7 11:57
 */

namespace app\em\model;

use think\Model;


class Team extends Common
{


    /*添加团队成员*/
    public  function inserts($name,$phone,$email)
    {

        $data = [
            't_name' => $name,
            't_phone' => $phone,
            't_email' => $email
        ];
        //var_dump($data);exit;
        $validate = validate('Team');
        //var_dump($validate->scene('add'));exit;
        //自动验证不通过
       if (!$validate->scene('add')->check($data)) {
                $this->error = $validate->getError();
                //var_dump($validate->scene('add')->check($data));exit;
                return false;
        }

       // $this->data($data)->allowField(true)->save();
      // var_dump($this->data($data));
        //return true;
        //验证通过 根据当前用户的手机号获取项目名称 和 id  -》保存数据
      //  $TeamModel = model('Team');

        $checkResult = $this->checkPhone($phone);
      // var_dump($checkResult);exit;
        if ($checkResult==true) {
            //$this->strict(false)->insert($data);
            $this->data($data)->allowField(true)->save();
            return true;
        } else {
            $this->error;
            return false;
        }

       /* $validate = Loader::validate('Team');

        if (!$validate->check($data)) {
            dump($validate->getError());

        }
            //  $data['create_time']=time();
            $check = Team::create($data);
            if ($check) {
                return true;
            } else {
                return false;
            }*/
        }

    //检查该手机号用户是否已在团队中
    public function checkPhone($phone){
        $checkPhone = [
            't_phone' => $phone,
        ];
        $validate = validate('Team');

        if(!$validate->scene('phone')->check($checkPhone)){
            //var_dump($validate->scene('phone')->check($checkPhone));exit;
            $this->error = $validate->getError();
            return false;
        }
        $map['phone'] = $phone;
       //var_dump($phone);exit;
      // $data = $this->field('id')->where($map)->find();

        $data = Team::where(["t_phone"=>$phone])->find();
       // var_dump($data);exit;
        if(!$data==null){
            $this->error = '此账号已经存在于团队中';
            return false;
        }
       return true;
        //var_dump($data);exit;
    }

    /*数据查询*/
    public static function lists(){
       // $lists=Team::where([])->order('id asc')->paginate(5);
         $lists=Team::where([])->order('id asc')->select();
        return $lists;
        var_dump($lists);exit;
    }


    /*删除团队成员*/
    public static function dels($id){
        $info=Team::where(["id"=>$id])->delete();
        if ($info) {
            return true;
        }else{
            return false;
        }
    }

    /*查询一条数据*/
    public static function finds($id){
        $info=Team::where(["id"=>$id])->find();
        return $info;
    }

    /*修改数据*/
    public static function updatemsgs($data,$id){
        // dump($data);
        $info=Team::where(["id"=>$id])->update($data);
        if ($info) {
            return true;
        }
    }

}
