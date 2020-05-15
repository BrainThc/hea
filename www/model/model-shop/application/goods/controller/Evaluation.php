<?php

namespace app\goods\controller;

class Evaluation extends Base
{
    public function indexBefore(){
        $param = $this->validate->index(input());
        if( $param === false ){
            $this->isExit = true;
            $this->code = 010701;
            $this->error = $this->validate->getError();
        }
        $where = [];
        //用户id
        if( isset($param['user_id']) && !empty($param['user_id']) ){
            $where['e.user_id'] = ['=',$param['user_id']];
        }
        //关联订单id
        if( isset($param['order_id']) && !empty($param['order_id']) ){
            $where['e.order_id'] = ['=',$param['order_id']];
        }
        //关联订单号
        if( isset($param['order_sn']) && !empty($param['order_sn']) ){
            $where['e.order_sn'] = ['=',$param['order_sn']];
        }
        //是否有图
        if( isset($param['has_img']) && !empty($param['has_img']) ){
            $where['e.images_list'] = [($param['has_img'] == 1 ? '<>' : '='),''];
        }
        //申请状态
        if( isset($param['verify']) && $param['verify'] != '' ){
            $where['e.verify'] = ['=',$param['verify']];
        }
        $this->model->field('e.*,g.goods_name')
            ->alias('e')
            ->join('goods g','g.id = e.goods_id')
            ->where($where)
            ->order('e.create_time','desc');
    }

}
