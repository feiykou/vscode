<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2018/1/31 15:08
 *
 */

namespace app\admin\model;


use think\Model;
use think\model\Merge;

class Article extends Model
{

    /**
     * 删除某一条数据
     * @param int $id
     * @return int
     */
    public function removeDataById($id=0){
        $data = [
            'id' => $id,
            'state' => 1
        ];

        $result = $this->where($data)->update(['state'=>0]);
        return $result;
    }

    /**
     * 删除多条数据
     */
    public function removeMoreDataById($idArr=[]){

        $data = [
            'id' => ['in',$idArr]
        ];
        $result = $this->where($data)->update(['state'=>0]);
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
            'state' => 1
        ];

        $result = $this->where($data)->find();
        return $result;
    }


    public function getAllCateData(){
        $cateData = ArtCate::alias('a1')
            ->field('a1.*,a2.name as pname')
            ->join('art_cate a2','a1.pid=a2.id','left')
            ->select();
        $sortArr = sortData($cateData);
        return $sortArr;
    }

    protected static function init(){
        self::event('after_insert', function($art){
            self::addImg($art);
        });
        self::event('after_update',function($art){
            self::addImg($art);
        });
    }

    /**
     * 添加图片到资源中，并且保存到数据库
     * @param $art  article模型
     */
    public static function addImg($art){
        $artJson = json_decode($art);
        if(array_key_exists("id",$artJson)){
            $id = $artJson->id;
        }else{
            $id = $art->updateWhere['id'];
        }
        //添加图片
        if($_FILES['img_url']['tmp_name']){
            $file = request()->file('img_url');
            $info = $file->move('upload');
            if($info){
                $img_url = DS . 'upload' . DS . $info->getSaveName();
                $art->get($id)->save(['img_url'=>$img_url]);
                self::saveImgSrc($img_url,$id);
            }
        }else{
            $art->get($id)->save(['img_url'=>'']);
            self::saveImgSrc(false,$id);
        }
    }

    private static function saveImgSrc($img_url,$art_id){
        //判断是否存在图片
        if($img_url){
            $imgMode = new Image();
            $imgMode->url = $img_url;
            $imgMode->save();
            $img_id = $imgMode->id;
            self::where('id','=',$art_id)->update(['img_id'=>$img_id]);
        }else{
            self::where('id','=',$art_id)->update(['img_id'=>null]);
        }
    }
}