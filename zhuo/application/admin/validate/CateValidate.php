<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2017/12/18 11:28
 *
 */

namespace app\admin\validate;


class CateValidate extends BaseValidate
{
    protected $rule = [
        'name' =>"require|isNotEmpty",
        'pid' =>"require",
    ];

    protected $message = [
        'name' => '分类不能为空',
        'pid' => '父类不能为空'
    ];
}