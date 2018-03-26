<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2017/12/18 10:56
 *
 */

namespace app\lib\exception;


class CateException extends BaseException
{
    public $code = 401;

    public $msg = "指定的部门不存在";

    public $errorCode = 40000;
}