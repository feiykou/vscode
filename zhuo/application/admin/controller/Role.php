<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2018/3/9 14:04
 *
 */

namespace app\admin\controller;


use think\Controller;
use app\admin\model\AuthGroup as RoleModel;
use app\admin\model\AuthRule as AuthRuleModel;

class Role extends Controller
{
    private $obj;
    function _initialize()
    {
        $rolemodel = new RoleModel();
        $this->obj = $rolemodel;
    }

    public function index(){

        $roleData = $this->obj->getAllData();

        return $this->fetch('',[
            'roleData' => $roleData
        ]);
    }


    public function add(){
        if(request()->isPost()){
            $data = request()->post();
            if($data['rules']){
                $data['rules'] = implode(',',$data['rules']);
            }
            $data['is_delete'] = 1;
            $result = db('auth_group')->insert($data);
            if($result){
                return $this->success("添加成功",url("/role"));
            }else{
                return $this->error('添加失败');
            }
        }else{
            $authrule = new AuthRuleModel();
            $ruleData = $authrule->authRuleTree();
            $this->assign([
                'ruleData' => $ruleData
            ]);
            return $this->fetch();
        }
    }

    public function edit($id=0){
        if(request()->isPost()){
            $data = request()->post();
            if($data['rules']){
                $data['rules'] = implode(',',$data['rules']);
            }
            /**
             * 对于状态status，如果为0，则不会传递到后台，解决方案
             */
            $_data = [];
            foreach ($data as $k => $v){
                $_data[] = $k;
            }
            if(!in_array('status',$_data)){
                $data['status'] = 0;
            }

            $result = $this->obj->updateDataById($id,$data);
            if($result){
                return $this->success("更新成功",url("/role"));
            }else{
                return $this->error('更新失败');
            }
        }else{
            $roleData = $this->obj->getDataById($id);
            $authrule = new AuthRuleModel();
            $ruleData = $authrule->authRuleTree();
            $rulesArr = explode(',',$roleData['rules']);
            return $this->fetch('',[
                'rulesArr' => $rulesArr,
                'roleData' => $roleData,
                'ruleData' => $ruleData
            ]);
        }
    }

    /**
     * 删除权限
     */
    public function del($id){
        $result = $this->obj->removeDataById($id);
        $role = RoleModel::get($id);
        $role->user()->detach();
        if($result){
            if($result){
                return json(['type'=>'success','success'=>"删除成功！","code"=>0]);
            }else{
                return json(['type'=>'error','error'=>"删除失败！","code"=>0]);
            }
        }
    }
}