<?php

namespace app\order\validate;

use app\picshow\model\Position;

class Order extends Base
{
    public function index($data){
        $rule = [
            'shop_id' => 'integer',
            'user_id' => 'integer',
            'channel_id' => 'integer',
            'order_sn' => 'alphaNum',
            'order_type' => 'integer',
            'verify' => 'integer',
            'pay_status' => 'integer',
            'start_time' => 'date',
            'end_time' => 'date',
            'getType' => 'chsDash'
        ];
        // 错误提示信息
        $message = [
            'shop_id' => '参数错误',
            'user_id' => '参数错误',
            'order_sn' => '单号仅为字母和数字',
            'order_type' => '参数错误',
            'verify' => '参数错误',
            'pay_status' => '参数错误',
            'start_time' => '日期格式不正确',
            'end_time' => '日期格式不正确',
            'getType' => '列表类型参数格式错误'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function getOrderInfo($data){
        $rule = [
            'order_id' => 'require|integer|>:0',
        ];
        // 错误提示信息
        $message = [
            'order_id' => '参数错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function getBuyGoodsList($data){
        $rule = [
            'user_id' => 'require|integer',
            'shop_id' => 'integer',
            'address_id' => 'integer',
            'shop_delivery' => 'array',
            'channel' => 'integer',
            'coupon_list' => 'array',
            'point' => 'float'
        ];
        // 错误提示信息
        $message = [
            'user_id' => '用户信息异常',
            'address_id' => '参数错误',
            'shop_delivery' => '参数错误',
            'shop_id' => '参数错误',
            'channel' => '渠道参数错误',
            'coupon_list' => '优惠券使用格式错误',
            'point' => '积分使用格式错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function getTotal($data){
        $rule = [
            'user_id' => 'require|integer',
            'address_id' => 'integer',
//            'delivery_id' => 'integer',
            'shop_delivery' => 'array',
            'shop_id' => 'integer',
            'channel' => 'integer',
            'coupon_list' => 'array',
            'point' => 'float'
        ];
        // 错误提示信息
        $message = [
            'user_id' => '用户信息异常',
            'address_id' => '参数错误',
//            'delivery_id'     => '参数错误',
            'shop_delivery' => '参数错误',
            'shop_id' => '参数错误',
            'channel' => '渠道参数错误',
            'coupon_list' => '优惠券使用格式错误',
            'point' => '积分使用格式错误'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function createOrder($data){
        $rule = [
            'user_id' => 'require|integer|>:0',
            'address_id' => 'require|integer|>:0',
//            'delivery_id' => 'require|integer|>:0',
            'shop_delivery' => 'array',
            'shop_id' => 'integer|>=:0',
            'channel' => 'integer',
            'self_take_shop' => 'array',
            'coupon_list' => 'array',
            'remark' => 'max:255',
            'point' => 'float',
        ];
        // 错误提示信息
        $message = [
            'user_id' => '用户信息异常',
            'address_id' => '参数错误',
            'shop_delivery' => '参数错误',
            'shop_id' => '参数错误',
            'channel' => '渠道参数错误',
            'coupon_list' => '优惠券使用格式错误',
            'point' => '积分使用格式错误'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function edit($data){
        $rule = [
            'order_id' => 'require|integer|>=:0',
            'description' => 'max:500'
        ];
        $message = [
            'order_id' => '参数错误'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function createPaymentOrder($data){
        $rule = [
            'order_id' => 'require|integer|>:0',
            'price' => 'require|float|>=:0',
        ];
        // 错误提示信息
        $message = [
            'order_id' => '参数错误',
            'price'     => '参数错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function replaceOrderGoods($data){
        $rule = [
            'order_goods_id' => 'require|integer|>:0',
            'item_id' => 'require|integer|>:0',
            'num' => 'require|integer|>:0',
        ];
        // 错误提示信息
        $message = [
            'order_goods_id' => '参数错误',
            'item_id'     => '参数错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function setOrderPay($data){
        $rule = [
            'order_id' => 'require|integer|>:0',
            'pay_status' => 'require|integer'
        ];
        // 错误提示信息
        $message = [
            'order_id' => '参数错误',
            'pay_status'   => '参数错误'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function orderVerify($data){
        $rule = [
            'order_id' => 'require|integer|>:0',
            'verify' => 'require|integer',
            'remark' => 'max:255'
        ];
        // 错误提示信息
        $message = [
            'order_id' => '参数错误',
            'verify'   => '参数错误',
            'remark'   => '备注内容超出限制'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function userOrderFinished($data){
        $rule = [
            'order_id' => 'require|integer|>:0',
            'user_id' => 'require|integer|>:0',
        ];
        // 错误提示信息
        $message = [
            'order_id' => '参数错误',
            'user_id' => '参数错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function setOrderFinished($data){
        $rule = [
            'order_id' => 'require|integer|>:0',
        ];
        // 错误提示信息
        $message = [
            'order_id' => '参数错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    //取消订单
    public function adminCancelOrder($data){
        $rule = [
            'order_id' => 'require|integer|>:0',
        ];
        // 错误提示信息
        $message = [
            'order_id' => '参数错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function userCancelOrder($data){
        $rule = [
            'user_id' => 'require|integer|>:0',
            'order_id' => 'require|integer|>:0',
        ];
        // 错误提示信息
        $message = [
            'user_id' => '参数错误',
            'order_id' => '参数错误',
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }
}
