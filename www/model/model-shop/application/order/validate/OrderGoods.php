<?php

namespace app\order\validate;

class OrderGoods extends Base
{

    public function indexBefore($data){
        $rule = [
            'id' => 'require|integer|>:0',
        ];
        // 错误提示信息
        $message = [
            'id' => '参数错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function getOrderGoodsList($data){
        $rule = [
            'order_id' => 'require|integer|>:0',
        ];
        // 错误提示信息
        $message = [
            'id' => '参数错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function goodsEvaluationSubmit($data){
        $rule = [
            'order_goods_id' => 'require|integer|>:0',
            'user_id' => 'require|integer|>:0',
            'desc' => 'require|max:500',
            'star' => 'require|integer|>=:1|<=:5',
            'img_list' => 'array'
        ];
        // 错误提示信息
        $message = [
            'order_goods_id' => '参数错误',
            'user_id' => '参数错误',
            'desc' => '评论描述不能为空',
            'img_list' => '图片参数错误',
            'star' => '评价不能为空'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function cancelGoods($data){
        $rule = [
            'id' => 'require|integer|>:0',
        ];
        // 错误提示信息
        $message = [
            'id' => '参数错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }
}
