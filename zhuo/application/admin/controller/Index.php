<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2018/1/26 10:23
 *
 */

namespace app\admin\controller;


use think\Controller;

class Index extends Controller
{
    function index(){
        return $this->fetch();
    }

    function main(){
        return $this->fetch();
    }
}