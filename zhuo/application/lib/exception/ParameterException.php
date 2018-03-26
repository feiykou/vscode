<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2017/12/18 11:35
 *
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;

    public $msg = "参数错误";

    public $errorCode = 10000;
}