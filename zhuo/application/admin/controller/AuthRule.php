<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2018/3/9 14:05
 *
 */

namespace app\admin\controller;


use think\Controller;
use app\admin\model\AuthRule as RuleModel;

class AuthRule extends Controller
{
    private $obj;
    public function _initialize()
    {
        $AuthRule = new RuleModel();
        $this->obj =$AuthRule;
    }

    /**
     * 权限列表页  --- 主入口
     * @return mixed
     */
    public function index(){
        $ruleData = $this->obj->getSortAllData();
        return $this->fetch('',[
            'ruleData' => $ruleData
        ]);
    }

    /**
     * 添加权限
     * @return mixed|\think\response\Json
     */
    public function add(){
       if(request()->isPost()){

           $data = request()->post();

           // 添加级别
            $plevel = $this->obj->where('id',$data['pid'])->field('level')->find();
            if($plevel){
                $data['level'] = $plevel['level']+1;
            }else{
                $data['level'] = 0;
            }

           $result = $this->obj->save($data);

           if($result){
               return json(['type'=>'success','success'=>"添加成功！","code"=>0]);
           }else{
               return json(['type'=>'error','error'=>"添加失败！","code"=>0]);
           }
       }else{
           $sortData = $this->obj->getSortAllData();
           $this->assign([
               'sortData' => $sortData
           ]);
       }
        return $this->fetch();
    }

    /**
     * 编辑权限
     * @return mixed
     */
    public function edit($id){
        if(request()->isPost()){
            $data = request()->post();
            // 添加级别
            $plevel = $this->obj->where('id',$data['pid'])->field('level')->find();
            if($plevel){
                $data['level'] = $plevel['level']+1;
            }else{
                $data['level'] = 0;
            }

            $result = $this->obj->updateDataById($id,$data);

            if($result){
                return json(['type'=>'success','success'=>"更新成功！","code"=>0]);
            }else{
                return json(['type'=>'error','error'=>"无更新内容！","code"=>0]);
            }
        }else{
            $sortData = $this->obj->getDeptData($id);
            $curIdData = $this->obj->getDataById($id);
            $this->assign([
                'sortData' => $sortData,
                'curIdData' => $curIdData
            ]);
        }

        return $this->fetch();
    }

    /**
     * 删除权限
     */
    public function del($id){
        $result = $this->obj->removeDataById($id);
        if($result){
            if($result){
                return json(['type'=>'success','success'=>"删除成功！","code"=>0]);
            }else{
                return json(['type'=>'error','error'=>"删除失败！","code"=>0]);
            }
        }
    }
}