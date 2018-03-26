<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2017/12/15 13:47
 *
 */

namespace app\admin\validate;


use app\lib\exception\BaseException;
use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        //获取http参数并校验
        $request = Request::instance();
        $params = $request->param();
        $result = $this->batch()->check($params);
        if(!$result){
            throw new ParameterException([
                'msg' => $this->error
            ]);
        }
        return true;
    }

    protected function isPositiveInteger($value){
        /*
         * 判断是否是正整数
         */
        if(is_numeric($value) && is_int($value + 0) && ($value + 0)>0){
            return true;
        }
        return false;
    }

    protected function iszeroInteger($value){
        /*
         * 判断是否是正整数
         */
        if(is_numeric($value) && is_int($value + 0) && ($value + 0)>=0){
            return true;
        }
        return false;
    }

    protected function isNotEmpty($value){
        if(!empty($value)){
            return true;
        }
        return false;
    }
}