<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/11
 * Time: 14:58
 */

namespace app\admin\model;


use think\Model;

class AuthRule extends Model
{
    /**
     * 无限极分类，添加父级链
     * @return array
     */
    public function authRuleTree(){
       $authRules = $this->select();
       return $this->sortData($authRules);
    }

    /**
     * 无限极分类，添加父级链
     * @param $arr
     * @param int $pid
     * @return array
     */
    public function sortData($arr,$pid=0){
        static $newArr = [];
        foreach ($arr as $k=>$v){
            if($v['pid']==$pid){
                $v['dataid'] = $this->getparentid($v['id']);
                $newArr[] = $v;
                $this->sortData($arr,$v['id']);
            }
        }
        return $newArr;
    }
    /**
     * 对权限进行无限极分类
     * @return array
     */
    public function getSortAllData(){
        $result = self::alias('a1')
            ->where('a1.status','=',1)
            ->field('a1.*,a2.name as pname')
            ->join('auth_rule a2','a1.pid=a2.id','left')
            ->select();
        $sortData = sortData($result);
        return $sortData;
    }

    /**
     * 获取父级id
     * @param $authRuleId
     * @return string
     */
    public function getparentid($authRuleId){
        $AuthRuleRes=$this->select();
        return $this->_getparentid($AuthRuleRes,$authRuleId,True);
    }

    public function _getparentid($AuthRuleRes,$authRuleId,$clear=False){
        static $arr=array();
        if($clear){
            $arr=array();
        }
        foreach ($AuthRuleRes as $k => $v) {
            if($v['id'] == $authRuleId){
                $arr[]=$v['id'];
                $this->_getparentid($AuthRuleRes,$v['pid'],False);
            }
        }
        asort($arr);
        $arrStr=implode('-', $arr);
        return $arrStr;
    }


    /**
     * 删除某一条数据
     * @param int $id
     * @return int
     */
    public function removeDataById($id=0){
        $data = [
            'id' => $id,
            'status' => 1
        ];

        $result = $this->where($data)->update(['status'=>0]);
        return $result;
    }

    /**
     * 获取某条数据
     * @param int $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getDataById($id=0){
        $data = [
            'id' => $id,
            'status' => 1
        ];

        $result = $this->where($data)->find();
        return $result;
    }

    /**
     * 更新某条数据
     * @param int $id
     * @param array $update_data
     * @return $this
     */
    public function updateDataById($id=0,$update_data=[]){
        $data = [
            'id' => $id,
            'status' => 1
        ];

        $result = $this->where($data)->update($update_data);
        return $result;
    }

    /**
     * 获取父级分类
     * @param $id
     * @return array
     */
    public function getDeptData($id){
        $model = new self();
        $ModelDatas = $model->select();
        $ids = "";
        $sonDatas = sortData($ModelDatas,$id);
        foreach ($sonDatas as $key=>$value){
            $ids.= $value['id'].',';
        }
        $ids .= trim($id);

        $data = [
            "id"=>['not in',$ids],
            "status" => 1
        ];

        // 排除id字符串的两种方式
        $parentDept = $model->where($data)->select();
        if(!$parentDept){
            throw new CateException();
        }else{
            // 对部门进行重新排序
            $parentSortDept = sortData($parentDept);
        }
        return $parentSortDept;
    }

}