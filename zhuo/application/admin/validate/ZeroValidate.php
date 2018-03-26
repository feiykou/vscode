<?php

namespace app\admin\validate;


class ZeroValidate extends BaseValidate
{
    protected $rule = [
        'id' =>"require|iszeroInteger",
    ];

    protected $message = [
        'id' => 'id必须是大于等于0的整数'
    ];
}