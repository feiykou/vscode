<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2017/12/11 14:24
 *
 */
return [
    // 视图输出字符串内容替换
    'view_replace_str'   => [
        '__ADMIN__'      => getRootBaseArr()['root'].'/'.request()->module(),
        '__MODULE__'     => getRootBaseArr()['base'].'/'.request()->module(),
        '__RES_ADMIN__'  => getRootBaseArr()['root'].'/static/admin'
    ],
    //开启trace调试
    'app_trace'          =>  true,
    'exception_handle'   => 'app\lib\exception\ExceptionHandler',
];