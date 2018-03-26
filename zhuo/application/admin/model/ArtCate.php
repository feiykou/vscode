<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2018/1/31 15:12
 *
 */

namespace app\admin\model;


use app\lib\exception\CateException;
use think\Model;

class ArtCate extends Model
{
    public static function getDeptData($id){
        $model = new self();
        $ModelDatas = $model->select();
        $ids = "";
        $sonDatas = sortData($ModelDatas,$id);
        foreach ($sonDatas as $key=>$value){
            $ids.= $value['id'].',';
        }
        $ids .= trim($id);

        // 排除id字符串的两种方式
        $parentDept = $model->where(["id"=>['not in',$ids]])->select();
        if(!$parentDept){
            throw new CateException();
        }else{
            // 对部门进行重新排序
            $parentSortDept = sortData($parentDept);
        }
        return $parentSortDept;
    }
}