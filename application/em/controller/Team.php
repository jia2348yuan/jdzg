<?php
/**
 * Description:
 * Author: zxd
 * DateTime: 2017/8/7 11:25
 组建团队*/

namespace app\em\controller;
use think\Controller;
use think\Request;
//class Team extends ApiCommon
//{
//    //显示团队列表
//    public function index(){
//        $teamModel = model('Team');
//        $param = $this->param;
//        var_dump($param);exit;
//
//    }
//
//    //添加团队成员
//    public function create(){
//      //  echo 11111;
//        $teamModel = model('Team');
//       // var_dump($teamModel);
//        $name = input('t_name');
//        $phone = input('t_phone');
//        $role = input('t_role');
//
//        $data = $teamModel->createTeam($name, $phone, $role);
////        var_dump($data);exit;
//        if (!$data) {
//            return resultArray(['error' => $teamModel->getError()]);
//        }
//        return resultArray(['data' => '添加成功']);
//   }
//}
class Team extends ApiCommon
{

    /*添加团队成员*/
    public function add()
    {
        //$list=\app\em\model\Team::lists();
        $userModel = model('Team');
        $name = input('t_name');
        $phone = input('t_phone');
        $email = input('t_email');



       // var_dump($email);exit;
        $data = $userModel->inserts($name, $phone, $email);
      // var_dump($data);exit;
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        }

       return resultArray(['data' => '添加成员成功']);

    }

        /*if (Request::instance()->isPost()) {
            $data=Request::instance()->post();  //获取全部的post变量
            //var_dump($data);exit;
            //验证数据有效性
            $insert=\app\em\model\Team::inserts($data);
            //var_dump($insert);exit;
            if ($insert) {
               // return $this->success("添加成功！","");
                return resultArray(['error' => $userModel->getError()]);
            }
        }*/
        /* $userModel = model('User');
        $phoneNumber = input('phone_number');  //手机账号
        $data = $userModel->getDataByPhoneNumber($phoneNumber);
        //var_dump($data);exit;
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        }
        return resultArray(['data' => $data]);
    }*/

      // return $this->fetch();


    /*删除成员*/
    public function del(){
        $teamModel = Model('Team');
        $id = input('id');
        $data=$teamModel->delDataById($id, $delSon = false);
        //var_dump($id);exit;
        if (!$data) {
            return resultArray(['error' => $teamModel->getError()]);
        }
        return resultArray(['data' => '删除成员成功']);
    }

    /*修改成员信息*/
    public function edit($id){
        $msg=\app\em\model\Team::finds($id);
        $this->assign("msg",$msg);
        return $this->fetch();
    }

    /*更新数据*/
    public function updatemsg($id)
    {
        if (Request::instance()->isPost()) {
            $data=Request::instance()->post();
            // dump($data);
            $edit=\app\em\model\Team::updatemsgs($data,$id);
            if ($edit) {
                $this->success("更新成功","index/index/index");
            }
        }
    }


    /*全选删除*/
    public function deleteall(){
        if (Request::instance()->isPost()) {
            $data=Request::instance()->post();
            foreach ($data as $k => $v) {
                $deleteall=\app\em\model\Team::dels($v);
            }
            if ($deleteall) {
                $this->success("删除成功","index/index/index");
            }
        }
    }
}
