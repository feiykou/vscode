<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2018/3/9 11:12
 *
 */

namespace app\admin\controller;


use app\admin\model\AuthGroup;
use think\Controller;
use app\admin\model\User as UserModel;
use think\Db;
use think\Exception;

class User extends Controller
{
    private $obj;
    function _initialize()
    {
        $this->obj = new UserModel();
    }

    public function index(){
        $userData = $this->obj->getAllData();
        $userLen = count($userData);
        for ($i=0;$i<$userLen;$i++){
            $group_tit_arr = [];
            $group_id_arr = [];
            // 获取关联表对象的数据
            $groupArr = $userData[$i]->group;
            foreach ($groupArr as $k=>$v){
                $group_tit_arr[] = $v['title'];
                $group_id_arr[] = $v['id'];
            }
            $userData[$i]->title = implode(' , ',$group_tit_arr);
            $userData[$i]->group_ids = implode(',',$group_id_arr);
        }
        return $this->fetch('',[
            'userData' => $userData
        ]);
    }


    public function add(){
        if(request()->isPost()){
            $data = request()->post();
            $user = new UserModel;
            $user->name = $data['name'];
            $user->passwords = md5($data['passwords']);
            $user->status = 1;
            $result = $user->save();

            if($result){
                $user_id = $user->id;
                $userModel = UserModel::get($user_id);
                $group_id_arr = $data['groups_id'];
                foreach ($group_id_arr as $k=>$v){
                    $userModel->group()->save($v);
                }
                return json(['type'=>'success','success'=>"更新成功！","code"=>0]);
            }else{
                return json(['type'=>'error','error'=>"更新失败！","code"=>0]);
            }
        }else{
            $roleDatas = $this->obj->getRoleAllData();
            if(empty($roleDatas)){
                $roleDatas = [];
            }
            $this->assign([
                "roleDatas" => $roleDatas
            ]);
            return $this->fetch();
        }
    }

    public function edit($id){
        $group_ids = request()->get('group_ids');
        $group_id_arr = explode(',',$group_ids);
        if(request()->isPost()){
            $data = request()->post();
            $user = UserModel::get($id);
            $user->name = $data['name'];
            $user->passwords = md5($data['passwords']);
            $user->status = 1;
            $result = $user->save();

            // 判断是否有角色
            if($group_id_arr != ['']){
                $user->group()->detach($group_id_arr);
            }
            // 判断是否有选中角色，进行添加
            if(keyInArray($data,'groups_id')){
                $group_res = $user->group()->save($data['groups_id']);
            }

            if($result || (isset($group_res) && $group_res)){
                return json(['type'=>'success','success'=>"添加成功！","code"=>0]);
            }else{
                return json(['type'=>'error','error'=>"添加失败！","code"=>0]);
            }

        }else{
            $groupsData = $this->obj->getRoleAllData();
            // 获取关联表相关内容
            $userData = UserModel::getUserWithRole($id);
            $userData['group_ids'] = $group_ids;

            return $this->fetch('',[
                'groupsData' => $groupsData,
                'userData' => $userData,
                'group_sel_arr' => $group_id_arr
            ]);
        }
    }

    public function del($id){
        Db::startTrans(); // 启动事务
        try{
            $user_result = $this->obj->removeDataById($id); // update 方法返回影响数据的条数，没修改任何数据返回 0
            $user = UserModel::get($id);
            $group_result = $user->group()->detach(); // 不管是成功还是失败，返回的都是null
            Db::commit(); // 整体提交上面事务
            if($user_result){
                return json(['type'=>'success','success'=>"删除成功！","code"=>0]);
            }else{
                return json(['type'=>'error','error'=>"删除失败！","code"=>0]);
            }
        }catch (Exception $ex){
            Db::rollback(); // 事务回滚
            throw $ex;
        }
    }



}