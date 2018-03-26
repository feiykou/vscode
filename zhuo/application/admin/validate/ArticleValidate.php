<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2018/2/9 17:29
 *
 */

namespace app\admin\validate;


class ArticleValidate extends BaseValidate
{
    protected $rule = [
        'title' =>"require|isNotEmpty",
        'category_id' =>"require",
//        'user_id' =>"require",
        'content' =>"require",
        'click_num' =>"require|iszeroInteger",
    ];

    protected $message = [
        'title' => '标题不能为空',
        'category_id' => '分类id不能为空',
        'content' => '内容不能为空',
        'click_num' => '点击数不能为空',
    ];
}