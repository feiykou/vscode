<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2017/12/18 16:30
 *
 */

namespace app\admin\validate;


class IDMustBePostiveInt extends BaseValidate
{
    protected $rule = [
        'id' => "require|isPositiveInteger",
    ];

    protected $message = [
        'id' => 'id必须是正整数'
    ];
}