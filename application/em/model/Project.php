<?php
/**
 * Description: 创建项目
 * Author: zxd
 * DateTime: 2017/7/26 15:04
 */

namespace app\em\model;

class Project extends Common
{
    //自动写入时间戳
    protected $createTime = 'create_time';
    protected $updateTime = false;
    protected $autoWriteTimestamp = true;

    //显示我的项目（当前账号在创建项目里创建的项目或加入团队管理的项目）
    public function getDataList($class, $page, $limit, $phone_number)
    {
        $map = [];
        $map['class'] = $class;
        $map['creator_phone_number|manager_phone_number'] = $phone_number;
       // $map['creator_phone_number|superior_manager_phone_number'] = $phone_number;

        //获取我的项目数量

        $dataCount = $this->where($map)->count('id');

        $list = $this->where($map);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list
            ->field('name,number,class,manager_phone_number,manager_realname')
            ->select();

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;
//var_dump($data);exit;
        return $data;

        //var_dump($data);exit;
    }

    /**
     * 创建项目
     * @author zxd
     * @param $name 项目名
     * @param $number 项目编号
     * @param $class 项目等级
     * @param $managerId 管理员ID
     * @param $managerPhoneNumber 管理员电话号码
     * @param $managerRealname 管理员真实姓名
     * @param $superiorManagerId 上级管理员ID
     * @param $superiorManagerPhoneNumber 上级管理员电话号码
     * @param $superiorManagerRealname 上级管理员真实姓名
     * @param $creatorId 创建者ID
     * @param $creatorPhoneNumber 创建者电话
     * @param $creatorRealname 创建者真实姓名
     * @return bool 返回布尔值
     */
    public function createData($name, $number, $class, $managerId, $managerPhoneNumber, $managerRealname, $superiorManagerId = 0, $superiorManagerPhoneNumber, $superiorManagerRealname, $creatorId, $creatorPhoneNumber, $creatorRealname)
    {
        $addData = [
            'name'  =>  $name,
            'number'  =>  $number,
            'class'  =>  $class,
            'manager_id'  =>  $managerId,
            'manager_phone_number' =>  $managerPhoneNumber,
            'manager_realname'  =>  $managerRealname,
            'superior_manager_id'  =>  $superiorManagerId,
            'superior_manager_phone_number'  =>  $superiorManagerPhoneNumber,
            'superior_manager_realname'  =>  $superiorManagerRealname,
            'creator_id'  =>  $creatorId,
            'creator_phone_number'  =>  $creatorPhoneNumber,
            'creator_realname'  =>  $creatorRealname
        ];
       // var_dump($addData);exit;
        //自动验证
        $validate = validate('Project');
      //  var_dump($validate);exit;
        //自动验证不通过
        if (!$validate->scene('add')->check($addData)) {
           //var_dump($validate->scene('add')->check($addData));exit;
            $this->error = $validate->getError();
            return false;
        }


        //如果是项目群（级别5）不需要上级管理员，其他情况下，如果添加项目的时候没有传上级管理员用户ID，则表示待添加上级管理员，如果传了，表示本次即将添加上级管理
        if ($class == 5) {//等级5的不需要上级
            $addData['superior_exist'] = 0;
        } else {
            if (!$superiorManagerId==null) {
                $addData['superior_exist'] = 2;//如果用户没有上级管理员， 就需要给其添加一个上级
                //var_dump($superiorManagerId);exit;



            } else {
                $addData['superior_exist'] = 1;
               //var_dump($addData);exit;
                //如果用户指定了上级管理员，那么获取上级管理员所在项目ID
                $data = $this->getSuperiorId($superiorManagerPhoneNumber, $class+1);
               // var_dump($data);exit;
                if (!$data) {
                    return $data;
                    //var_dump($data);exit;
                }
                $checkResult = $this->checkManager($data['id'], $managerPhoneNumber);
                if ($checkResult) {
                    $this->error = '该用户已经属于本项目管理';
                    return false;
                }

                //var_dump($data);exit;
                $addData['pid'] = $data['id'];
                //var_dump($addData['pid']);exit;
            }

            //创建子项目或者单位工程关联（覆盖）另一个账号创建的子项目
            $map['manager_phone_number'] = $managerPhoneNumber;
            $map['class'] = $class;
            $relationProject = $this->where($map)->find();

            //var_dump($relationProject);exit;

             //项目关系状态关联请求等待同意
            if ($relationProject) {
                $addData['relation_state'] = 0;
            } else {
                $addData['relation_state'] = 1;
            }
        }

        $this->data($addData)->allowField(true)->save();
        return true;
    }

    /**
     * 根据手机号获取上级管理员项目id
     * @author zxd
     * @param $managerPhoneNumber 管理员手机号
     * @param $class 项目级别
     * @return array|bool|false|\PDOStatement|string|Model 返回项目ID
     */
    public function getSuperiorId($superiorManagerPhoneNumber, $class) {
        $checkData = [
            'superior_manager_phone_number'  => $superiorManagerPhoneNumber
        ];
        //自动验证
        $validate = validate('Project');
        //自动验证不通过
        if (!$validate->scene('phone')->check($checkData)) {
            $this->error = $validate->getError();
            return false;
        }
        $map['manager_phone_number'] = $superiorManagerPhoneNumber;
        $map['class'] = $class;
        $data = $this->field('id')->where($map)->find();
       //var_dump($data);exit;
        if (!$data) {
            $this->error = '此管理员没有创建过项目';
            return false;
        }
        return $data;
      //var_dump($data);exit;
    }

    //判断当前管理员是否已经属于当前要添加的上级项目
    public function checkManager($pid, $managerPhoneNumber) {
        $map['pid'] = $pid;
        $map['manager_phone_number'] = $managerPhoneNumber;
        $data = $this->field('id')->where($map)->select();
       //var_dump($data);exit;
        if (!$data) {
            return false;
        }
        return true;
    }






}