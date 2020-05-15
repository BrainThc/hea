<?php

/**
 *  商品销售参数组规格
 */

namespace app\goods\validate;

class AttributeGroup extends Base
{
    /**
     * @param  [type] $data [验证数据]
     * @return [type]       [description]
     */
    public function getAttrGroupList($data){
        $rule = [
            'page' => 'integer',
            'limit' => 'integer'
        ];
        // 返回验证结果
        return $this->validate($rule, $data);
    }

    /**
     * [getGoodsTagSingle 获取单个信息]
     * @param  [type] $data [验证数据]
     * @return [type]       [description]
     */
    public function getAttrGroupInfo($data){
        $rule = [
            'id' => 'require|integer|>:0'
        ];
        // 错误提示信息
        $message = [
            'id' => '参数错误'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function add($data){
        $rule = [
            'group_name' => 'require'
        ];
        // 错误提示信息
        $message = [
            'group_name' => '参数组名不能为空'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function saveAttrGroupInfo($data){
        $rule = [
            'id'            => 'require|integer|>:0',
            'group_name'    => 'require',
            'attr'          => 'array'
        ];
        // 错误提示信息
        $message = [
            'id' => '参数错误',
            'group_name' => '参数组名不能为空',
            'attr.array' => '参数错误'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }

    public function deleted($data){
        $rule = [
            'id' => 'require|>:0'
        ];
        // 错误提示信息
        $message = [
            'id' => '参数错误'
        ];
        // 返回验证结果
        return $this->validate($rule, $data,$message);
    }


}
