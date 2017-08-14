<?php
/**
 * Description: 项目类，需要做登录判断
 * Author: zxd
 * DateTime: 2017/7/25 14:35
 */

namespace app\em\controller;

class Project extends ApiCommon
{
    //显示我的项目（当前账号在创建项目里创建的项目或加入团队管理的项目）
    public function index()
    {
        $projectModel = model('Project');
        $param = $this->param;
       // var_dump($projectModel);exit;
        $class = !empty($param['class']) ? $param['class']: 5;




        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $loginInfo = session('user');
       // var_dump($projectModel);exit;


        $phone_number = $loginInfo['phone_number'];
       // var_dump($loginInfo);exit;
        $data = $projectModel->getDataList($class, $page, $limit, $phone_number);

        return resultArray(['data' => $data]);
        //var_dump($data);exit;
    }

    //项目详情
    public function Projectlist()
    {

    }

    //创建项目
    public function create() {
        $projectModel = model('Project');
        $name = input('name');
        $number = input('number');
        $class = input('class');
        $managerId = input('manager_id');
        $managerPhoneNumber = input('manager_phone_number');
        $managerRealname = input('manager_realname');
        $superiorManagerId = input('superior_manager_id');
        $superiorManagerPhoneNumber = input('superior_manager_phone_number');
        $superiorManagerRealname = input('superior_manager_realname');

        $creatorId = session('user')['id'];
        $creatorPhoneNumber = session('user')['phone_number'];
        $creatorRealname = session('user')['realname'];
//        $creatorId = 30;
//        $creatorPhoneNumber = '18782917916';
//        $creatorRealname = '周小冬';
        $data = $projectModel->createData($name, $number, $class, $managerId, $managerPhoneNumber, $managerRealname, $superiorManagerId, $superiorManagerPhoneNumber, $superiorManagerRealname, $creatorId, $creatorPhoneNumber, $creatorRealname);
        if (!$data) {
            return resultArray(['error' => $projectModel->getError()]);
        }
        return resultArray(['data' => '创建成功']);
    }

    //删除项目
    public function delete()
    {
        $projectModel = model('Project');
        $id = input('id');
        $data = $projectModel->delDataById($id, $delSon = false);
        //var_dump($id);exit;
        if (!$data) {
            return resultArray(['error' => $projectModel->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    //批量删除项目
    public function deletes()
    {
        $userModel = model('User');
        $ids = input('ids');
        $data = $userModel->delDatas($ids);
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }



}