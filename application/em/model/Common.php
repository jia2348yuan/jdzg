<?php
/**
 * Description: 公共模型,所有模型都可继承此模型，基于RESTFUL CRUD操作
 * Author: zxd
 * DateTime: 2017/7/28 17:01
 */

namespace app\em\model;

use think\Model;

class Common extends Model
{
    /**
     * 根据主键获取详情
     * @author zxd
     * @param string $id ID主键
     * @return bool|static
     */
    public function getDataById($id = '')
    {
        $data = $this->get($id);
        if (!$data) {
            $this->error = '暂无此数据';
            return false;
        }
        return $data;
    }

    /**
     * 新建
     * @author zxd
     * @param $param
     * @return bool
     */
    /*
    public function createData($param)
    {

        // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }
        try {
            $this->data($param)->allowField(true)->save();
            return true;
        } catch(\Exception $e) {
            $this->error = '添加失败';
            return false;
        }
    }
    */

    /**
     * 编辑
     * @author zxd
     * @param $param
     * @param $id
     * @return bool
     */
    public function updateDataById($param, $id)
    {
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = '暂无此数据';
            return false;
        }

        // 验证
        $validate = validate($this->name);
        if (!$validate->check($param)) {
            $this->error = $validate->getError();
            return false;
        }

        try {
            $this->allowField(true)->save($param, [$this->getPk() => $id]);
            return true;
        } catch(\Exception $e) {
            $this->error = '编辑失败';
            return false;
        }
    }

    /**
     * 根据id删除数据
     * @author zxd
     * @param string $id 主键ID
     * @param bool $delSon 是否删除子孙数据
     * @return bool
     */
    public function delDataById($id = '', $delSon = false)
    {
        $this->startTrans();
        try {
            $this->where($this->getPk(), $id)->delete();
            if ($delSon && is_numeric($id)) {
                // 删除子孙
                $childIds = $this->getAllChild($id);
                if($childIds){
                    $this->where($this->getPk(), 'in', $childIds)->delete();
                }
            }
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->error = '删除失败';
            $this->rollback();
            return false;
        }
    }


    /**
     * 批量删除数据
     * @author zxd
     * @param array $ids 主键数组
     * @param bool $delSon 是否删除子孙数据
     * @return bool
     */
    public function delDatas($ids = [], $delSon = false)
    {
        if (empty($ids)) {
            $this->error = '删除失败';
            return false;
        }

        // 查找所有子元素
        if ($delSon) {
            foreach ($ids as $k => $v) {
                if (!is_numeric($v)) continue;
                $childIds = $this->getAllChild($v);
                $ids = array_merge($ids, $childIds);
            }
            $ids = array_unique($ids);
        }

        try {
            $this->where($this->getPk(), 'in', $ids)->delete();
            return true;
        } catch (\Exception $e) {
            $this->error = '操作失败';
            return false;
        }

    }

    /**
     * 批量启用、禁用
     * @author zxd
     * @param array $ids 主键数组
     * @param int $status 状态1启用0禁用
     * @param bool $delSon 是否启用或禁用子孙数组
     * @return bool
     */
    public function enableDatas($ids = [], $status = 1, $delSon = false)
    {
        if (empty($ids)) {
            $this->error = '操作失败';
            return false;
        }

        // 查找所有子元素
        if ($delSon && $status === '0') {
            foreach ($ids as $k => $v) {
                $childIds = $this->getAllChild($v);
                $ids = array_merge($ids, $childIds);
            }
            $ids = array_unique($ids);
        }
        try {
            $this->where($this->getPk(),'in',$ids)->setField('status', $status);
            return true;
        } catch (\Exception $e) {
            $this->error = '操作失败';
            return false;
        }
    }

    /**
     * 获取所有子孙
     * @author zxd
     * @param $id
     * @param array $data
     * @return array
     */
    public function getAllChild($id, &$data = [])
{
    $map['pid'] = $id;
    $childIds = $this->where($map)->column($this->getPk());
    if (!empty($childIds)) {
        foreach ($childIds as $v) {
            $data[] = $v;
            $this->getAllChild($v, $data);
        }
    }
    return $data;
}
}
