<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2018/3/14 17:53
 *
 */

namespace app\admin\model;


use think\Model;

class AuthGroup extends Model
{
    /**
     * 对权限进行无限极分类
     * @return array
     */
    public function getSortAllData(){
        $result = AuthRule::alias('a1')
            ->where('a1.status','=',1)
            ->field('a1.*,a2.name as pname')
            ->join('auth_rule a2','a1.pid=a2.id','left')
            ->select();
        $sortData = $this->sortData($result);
        return $sortData;
    }

    /**
     * 获取状态为1的全部数据
     */
    public function getAllData(){
        $data = [
            'is_delete' => 1
        ];
        $result = db('auth_group')->where($data)->select();
        return $result;
    }

    /**
     * 用户组获取某条数据
     * @param int $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getDataById($id=0){
        $data = [
            'id' => $id,
        ];

        $result = db('auth_group')->where($data)->find();
        return $result;
    }

    /**
     * 更新某条数据
     */
    public function updateDataById($id=0,$roledata){
        $data = [
            'id' => $id,
        ];
        $result = db('auth_group')->where($data)->update($roledata);
        return $result;
    }

    /**
     * 删除某一条数据
     * @param int $id
     * @return int
     */
    public function removeDataById($id=0){
        $data = [
            'id' => $id,
            'is_delete' => 0
        ];

        $result = db('auth_group')->update($data);
        return $result;
    }

    /**
     * 用户和角色多对多关联
     * belongsToMany参数：
     *      AuthGroup：关联表模型
     *      auth_group_access：中间表
     *      group_id和uid：中间表字段
     * @return \think\model\relation\BelongsToMany
     */
    public function user(){
        return $this->belongsToMany('User','auth_group_access','uid','group_id');
    }
}