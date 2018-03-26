<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Request;

/**
 *  获取请求根部和根目录
 *
 * @return array 返回的是请求根部和根目录
 */
function getRootBaseArr(){
    // 基础替换字符串
    $request = Request::instance();
    $base    = $request->root();
    $root    = strpos($base, '.') ? ltrim(dirname($base), DS) : $base;
    if ('' != $root) {
        $root = '/' . ltrim($root, '/');
    }
    return [
        "root"=>$root,
        "base"=>$base
    ];
}

function sortData($arr,$pid=0,$level=0,$html="----"){
    $newArr = [];
    foreach ($arr as $k=>$v){
        if($v['pid']==$pid){
            $v['level'] = $level;
            $v['html'] = $html;
            $newArr[] = $v;
            $newArr = array_merge($newArr,sortData($arr,$v['id'],$v['level']+1));
        }
    }
    return $newArr;
};



/**
 * 验证手机号是否正确
 * @author honfei
 * @param number $mobile
 */
function isMobile($mobile) {
    if (!is_numeric($mobile)) {
        return false;
    }
    return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
};


/**
 * 检测某key是否存在数组中
 */
function keyInArray($data,$key){
    $_data = [];
    foreach ($data as $k => $v){
        $_data[] = $k;
    }
    if(in_array($key,$_data)){
        return true;
    }else{
        return false;
    }
};


















