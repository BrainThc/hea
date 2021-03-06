<?php
namespace app\order\controller;

use think\Exception;

class OrderGoods extends Base
{
    public function getOrderGoodsList(){
        if( !$param = $this->validate->getOrderGoodsList(input('post.')) ) {
            return errorJson(0000201, $this->validate->getError());
        }
        $goods_list = $this->model->getItemList($param['order_id']);
        return successJson($goods_list,'success');
    }

    //用户订单评价提交
    public function goodsEvaluationSubmit(){
        $code = 000001;
        if( !$param = $this->validate->goodsEvaluationSubmit(input('post.')) ){
            return errorJson($code,$this->validate->getError());
        }
        $order_goods_id = $param['order_goods_id'];
        $user_id = $param['user_id'];
        $star = $param['star'];
        $desc = $param['desc'];
        $img_list = $param['img_list'];
        $this->model->startTrans();
        try{
            $where['og.id'] = $order_goods_id;
            $where['o.user_id'] = $user_id;
            $orderModel = new \app\order\model\Order();
            //获取商品单信息
            $order_goods_info = $this->model->alias('og')
                ->field('o.user_id,o.order_sn,og.*,o.finished_status,o.evaluation_status')
                ->join($orderModel->getTable().' o','o.order_id = og.order_id')
                ->where($where)
                ->find();
            if( empty($order_goods_info) ){
                $code = 000010;
                throw new Exception('订单信息获取失败');
            }
            //检查订单状态
            if( $order_goods_info['status'] <> 0 && $order_goods_info['finished_status'] == 0 ){
                $code = 000011;
                throw new Exception('商品订单不在可评论状态');
            }
            //处理评论数据
            $t = date('Y-m-d H:i:s');
            $goodsEvaluationModel = new \app\goods\model\Evaluation();
            $insertData = [];
            $insertData['user_id'] = $user_id;
            $insertData['order_id'] = $order_goods_info['order_id'];
            $insertData['order_sn'] = $order_goods_info['order_sn'];
            $insertData['order_goods_id'] = $order_goods_info['id'];
            $insertData['goods_id'] = $order_goods_info['goods_id'];
            $insertData['goods_item_id'] = $order_goods_info['goods_item_id'];
            $insertData['star'] = $star;
            $insertData['content'] = $desc;
            $insertData['images_list'] = empty($img_list) ? '' : json_encode($img_list);
            $insertData['create_time'] = $t;
            $insertData['update_time'] = $t;
            if( getSystemSet('GOODS_EVALUATION_AUTO') == 'on' ){
                $insertData['verify'] = 1;
            }
            if( !$goodsEvaluationModel->insert($insertData) ){
                throw new Exception('网络错误，评论提交失败');
            }
            //检查原订单是否已经更改状态
            if( $order_goods_info['evaluation_status'] == 0 ){
                $updateData['evaluation_status'] = 1;
                $updateData['evaluation_time'] = $t;
                $updateData['update_time'] = $t;
                if( !$orderModel->where('order_id',$order_goods_info['order_id'])->update($updateData) ){
                    throw new Exception('网络错误，订单状态更新失败');
                }
            }
            $this->model->commit();
        }catch( Exception $e ){
            $this->model->rollback();
            return errorJson($code,$e->getMessage());
        }
        return successJson('success','提交成功');
    }

    /**
     * 订单取消商品
     * 这里的取消商品 不作为主订单的取消订单业务模块内容
     * 即 若订单的所有商品都取消退款了 也不能视为订单已取消
     * @return \think\response\Json
     */
    public function cancelGoods(){
        $code = 020101;
        if( !$param = $this->validate->cancelGoods(input('post.')) ){
            return errorJson($code,$this->validate->getError());
        }
        $orderGoodsId = $param['id'];
        $this->model->startTrans();
        try{
            //获取商品订单信息
            $orderModel = new \app\order\model\Order();
            $orderGoodsInfo = $this->model
                ->field('o.order_id,
                o.order_sn,
                o.goods_price,
                o.order_price,
                o.coupon_price,
                o.point_price,
                o.discount_price,
                o.ship_price,
                o.pay_price,
                o.shop_id,
                o.verify,
                o.pay_status,
                o.order_status,
                og.erp_code,
                og.num,
                og.item_price,
                og.coupon_price,
                og.point_price,
                og.discount_price,
                og.order_goods_price,
                og.status,
                og.gift_id')
                ->alias('og')
                ->join($orderModel->getTable().' o','o.order_id = og.order_id')
                ->where('og.id',$orderGoodsId)
                ->find();
            if( empty($orderGoodsInfo) ){
                $code = 020110;
                throw new Exception('商品订单信息获取失败');
            }
            if( $orderGoodsInfo['verify'] == 1 ){
                $code = 020110;
                throw new Exception('订单已审核不能此操作');
            }
            //库存归档
            $erp_info = explode(',',$orderGoodsInfo['erp_code']);
            $shopDepotModel = new \app\depot\model\ShopDepot();
            $shopDepotChangeModel = new \app\depot\model\ShopDepotChange();
            if( !$shopDepotModel->SaleInventoryOperation($orderGoodsInfo['order_sn'],$shopDepotChangeModel::TYPE_SALES_RETURN ,$orderGoodsInfo['shop_id'] , $erp_info[0],$erp_info[1],$orderGoodsInfo['num']) ) {
                $code = 020111;
                throw new Exception('商品回库失败');
            }
            //检查订单是否已付款 并且不是赠品 已支付则退钱
            if( $orderGoodsInfo['pay_status'] == 1 && $orderGoodsInfo['gift_id'] == 0 ){
                //这里财务退款 //退款审核单

            }
            //更新商品状态
            $update['status'] = 30;
            $where['id'] = $orderGoodsId;
            if( !$this->model->where($where)->update($update) ){
                $code = 020112;
                throw new Exception('订单信息更新失败');
            }
            //日志记录
            $user_id = 1;
            $orderLogModel = new \app\order\model\OrderLog();
            if( !$orderLogModel->markLog($user_id,$orderGoodsInfo['order_id'],'订单审核前商品'.$orderGoodsInfo['erp_code'].'取消且退款',$orderLogModel::OPERATOR_TYPE_ADMIN) ){
                $code = 020113;
                throw new Exception('订单记录失败');
            }
            //检查订单状态是否需要更变
            if( !$orderModel->checkOrderStatus($orderGoodsInfo['order_id']) ){
                $code = 020114;
                throw new Exception($orderModel->getError());
            }
            actionLogs('订单审核前商品'.$orderGoodsInfo['erp_code'].'取消且退款',$this->model);
            $this->model->commit();
        }catch( Exception $e ){
            $this->model->rollback();
            return errorJson($code,$e->getMessage());
        }
        return successJson('success','操作成功');
    }
}
