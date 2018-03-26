<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2018/1/30 17:45
 *
 */

namespace app\admin\controller;



use app\admin\model\ArtCate;
use app\admin\validate\ArticleValidate;
use app\admin\validate\CateValidate;
use app\admin\validate\ZeroValidate;
use think\Controller;
use app\admin\model\Article as ArticleModel;


class Article extends Controller
{

    private $obj;
    public function _initialize()
    {
       $this->obj = model('article');
    }

    /**
     * 文章列表
     * @return mixed
     */
    function artlist(){
        $artData = ArticleModel::alias('a1')
            ->where("state",'=','1')
            ->field('a1.*,a2.name as pname')
            ->order(['id'=>"desc"])
            ->join('art_cate a2','a1.category_id=a2.id','left')
            ->paginate();
        $page = $artData->render();
        $this->assign('artData',$artData);
        return $this->fetch('',['page'=>$page]);
    }

    /**
     * 文章编写
     */
    function artcon(){
        $cateData = ArtCate::all();
        $sortArr = sortData($cateData);
        $this->assign('cateArr',$sortArr);
        return $this->fetch();
    }


    /**
     * 文章添加
     */
    function artAdd(){
        if(request()->isPost()){
            (new ArticleValidate())->goCheck();
            $data = input('post.','','htmlentities');
            $result = $this->obj->save($data);
            if($result){
                return $this->success('添加成功',url('/artlist'));
            }else{
                return $this->error('添加失败');
            }
        }
    }

    /**
     * 文章删除
     */
    function removeArt($id){
        $result = model("article")->removeDataById($id);
        if($result){
            return json(['type'=>'success','success'=>"删除成功！","code"=>0]);
        }else{
            return json(['type'=>'success','error'=>"删除失败！","code"=>0]);
        }
    }

    /**
     * 删除多条文章
     */
    function removeMoreArt(){
        $idArr = input('post.')['idsArr'];
        $result = $this->obj->removeMoreDataById($idArr);
        if($result){
            return json(['type'=>'success','success'=>"删除成功！","code"=>0]);
        }else{
            return json(['type'=>'success','error'=>"删除失败！","code"=>0]);
        }
    }

    /**
     * 文章编辑
     */

    function artEdit($id){
        $getData = $this->obj->getDataById($id);
        if($getData['img_url']){
         $getData['img_url'] = config('setting.img_prefix').$getData['img_url'];
        }
        $cateData = $this->obj->getAllCateData();
        return $this->fetch('',[
            'artData'=>$getData,
            'cateArr'=>$cateData
        ]);
    }

    function artEditUpdate($id=0){
        $artconData = input('post.');
        $result = model('article')->save($artconData,['id' => $id]);
        if($result){
            return $this->success("更新成功",'/artlist');
        }else{
            return $this->error('暂无更新');
        }

    }



    /**
     * 文章分类
     * @return mixed
     */
    function artcate(){
        $cateData = ArtCate::alias('a1')
            ->field('a1.*,a2.name as pname')
            ->join('art_cate a2','a1.pid=a2.id','left')
            ->paginate();
        $page = $cateData->render();
        $sortArr = sortData($cateData);
        $this->assign('cateArr',$sortArr);
        return $this->fetch('',['page'=>$page]);
    }

    // 添加分类
    function cateAdd(){
        // 判断请求类型
        if(request()->isPost()){
            (new CateValidate())->goCheck();
            $cateData = input("post.",'','htmlentities');
            // 判断是否存在同名类
            $name = $cateData['name'];
            $exists = ArtCate::where('name','=',$name)->find();
            if($exists){
                return json(['type'=>'err_exist','err_exist'=>"存在同名分类！","code"=>1]);
            }
            $rsCate = ArtCate::insert($cateData);
            if($rsCate){
                return json(['type'=>'success','success'=>'添加成功','code'=>0]);
            }else{
                return json(['type'=>'error','error'=>'添加失败',"code"=>1]);
            }
        }
    }
    // 编辑分类
    function cateEdit($id=1){
        $cateIdData = ArtCate::where('id','=',$id)->find();
        $cateData = ArtCate::alias('a1')
            ->field('a1.*,a2.name as pname')
            ->join('art_cate a2','a1.pid=a2.id','left')
            ->select();

        $parentData = ArtCate::getDeptData($id);
        $this->assign('cateArr',$parentData);
        if($cateIdData){
            $this->assign('cateData',$cateIdData);
            return $this->fetch();
        }else{
            return $this->error('获取分类数据错误');
        }
    }
    // 处理编辑分类
    function handleEdit($id=''){
        if(request()->isPost()){
            (new CateValidate())->goCheck();
            $cateData = request()->post();
            // 判断是否存在同名类
            $name = $cateData['name'];
            $exists = ArtCate::where(['name'=>$name,'id'=>['neq',$id]])->find();
            if($exists){
                return json(['type'=>'err_exist','err_exist'=>"存在同名分类！","code"=>1]);
            }

            $rsCate = ArtCate::where('id','=',$id)->update($cateData);
            if($rsCate){
                return json(['type'=>'success','success'=>'添加成功','code'=>0]);
            }else{
                return json(['type'=>'error','error'=>'更新失败',"code"=>1]);
            }
        }
    }

    // 删除分类
    function cateDel($id=""){
        (new ZeroValidate())->goCheck();
        // 锁
        $mark = true;
        // 判断是否存在子类
        $curCate = ArtCate::all();
        $son = sortData($curCate,$id);
        if(!empty($son)){
            $mark = false;
        }

        if(!$mark){
            return json(['type'=>'exits','exits'=>'存在子类','code'=>2]);
        }else{
            $result = ArtCate::where('id','=',$id)->delete();
            if($result){
                return json(['type'=>'success','success'=>'删除成功','code'=>0]);
            }else{
                return json(['type'=>'success','success'=>'删除失败','code'=>1]);
            }
        }
    }





}