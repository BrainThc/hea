<?php
namespace app\order\controller;

use think\Exception;

class OrderReturn extends Base
{

    public function indexBefore(){
        $param = $this->validate->index(input());
        if( $param === false ){
            $this->isExit = true;
            $this->code = 020201;
            $this->error = $this->validate->getError();
        }
        $where = [];
        $this->model->alias('org');
        if( isset($param['type']) && isset($this->model::$map_return_type[$param['type']]) ){
            $where['org.return_type'] = ['=',$param['type']];
        }
        //会员id
        if( isset($param['user_id']) && $param['user_id'] > 0 ){
            $where['org.user_id'] = ['=',$param['user_id']];
        }
        //申请单号
        if( isset($param['order_return_sn']) && $param['order_return_sn'] != '' ){
            $where['org.order_return_sn'] = ['=',$param['order_return_sn']];
        }
        //时间筛选
        if( !empty($param['start_time']) && empty($param['end_time']) ) {
            $where['org.create_time'] = ['>=', $param['start_time']];
        }else if( empty($param['start_time']) && !empty($param['end_time']) ){
            $where['org.create_time'] = ['<=', $param['end_time']];
        }else if( !empty($param['start_time']) && !empty($param['end_time']) ){
            $where['org.create_time'] = ['between', [$param['start_time'],$param['end_time']]];
        }
        //订单号
        if( isset($param['order_sn']) && $param['order_sn'] != '' ){
            $where['o.order_sn'] = ['=',$param['order_sn']];
            $orderModel = new \app\order\model\Order();
            $this->model->join($orderModel->getTable().' o','o.order_id = org.order_id');
        }
        $this->model->where($where)->order('org.create_time','desc');
    }

    //创建退换货单
    public function createOrderReturn(){
        $code = 000001;
        if( !$param = $this->validate->createOrderReturn(input('post.')) ) {
            return errorJson($code, $this->validate->getError());
        }
        $user_id = $param['user_id'];
        $order_goods_id = $param['order_goods_id'];
        $return_reason_id = $param['return_reason_id'];
        $return_type = $param['return_type'];
        $return_num = $param['return_num'];
        $description = $param['description'];
        $img_list = $param['img_list'];
        $this->model->startTrans();
        try{
            //获取订单信息
            $orderModel = new \app\order\model\Order();
            $orderGoodsModel = new \app\order\model\OrderGoods();
            $order_goods_info = $orderGoodsModel->field('og.*,
            o.verify as order_verify,
            o.shipping_status,
            o.finished_status,
            o.finished_time,
            o.order_status,
            o.create_time
            ')
                ->alias('og')
                ->join($orderModel->getTable().' o','o.order_id = og.order_id')
                ->where(['og.id'=>$order_goods_id,'o.user_id'=>$user_id])->find();
            if( empty($order_goods_info) ){
                $code = 000010;
                throw new Exception('商品订单信息获取失败');
            }
            if( $order_goods_info['status'] == 40 ){
                $code = 000011;
                throw new Exception('商品单状态不可申请退换');
            }
            if( $order_goods_info['order_status'] != 0 || $order_goods_info['order_verify'] == 0 ){
                $code = 000011;
                throw new Exception('当前订单状态不可申请退换');
            }
            //创建退换单
            if( empty($this->model::$map_return_type[$return_type]) ){
                $code = 000011;
                throw new Exception('申请类型不存在');
            }
            if( $order_goods_info['ship_status'] == 0 && $return_type == $this->model::RETURN_TYPE_REFUND ){
                $code = 000011;
                throw new Exception('当前订单状态不能申请退货');
            }
            //检查是否符合申请时间段内
            //获取已申请的列表
            $num = $this->model->getGoodsRefundNum($order_goods_id);
            //数量检查
            $num = bcadd($num,$return_num,0);
            if( $num > $order_goods_info['num'] ){
                $code = 000012;
                throw new Exception('超出可申请数量');
            }
            //获取退换类型
            $orderReturnReasonModel = new \app\order\model\OrderReturnReason();
            $reason_info = $orderReturnReasonModel->where('id',$return_reason_id)->find();
            if( empty($reason_info) ){
                $code = 000013;
                throw new Exception('问题类型不存在');
            }
            $t = date('Y-m-d H:i:s');
            $insertData['user_id'] = $user_id;
            $insertData['order_id'] = $order_goods_info['order_id'];
            $insertData['order_return_sn'] = $this->model::$map_return_type[$return_type]['code'].$orderModel->createSn();
            $insertData['order_goods_id'] = $order_goods_info['id'];
            $insertData['return_num'] = $return_num;
            $insertData['return_content'] = trim($description);
            $insertData['return_images'] = json_encode($img_list);
            $insertData['return_type'] = $return_type;
            $insertData['return_reason_id'] = $return_reason_id;
            $insertData['create_time'] = $t;
            $insertData['update_time'] = $insertData['create_time'];
            if( !$return_id = $this->model->insertGetId($insertData) ){
                $code = 000014;
                throw new Exception('网络错误');
            }
            //更新订单状态
            $updateData['status'] = $this->model::$map_return_type[$return_type]['status_start'];
            $updateData['update_time'] = $t;
            if( !$orderGoodsModel->where('id',$order_goods_id)->update($updateData) ){
                $code = 000015;
                throw new Exception('订单状态更新失败');
            }
            //订单日志记录
            $log_msg = '用户申请'.$order_goods_info['erp_code'].' × '.$return_num.'件'.$this->model::$map_return_type[$return_type]['desc'].' 申请退换单号：'.$insertData['order_return_sn'];
            $orderLogModel = new \app\order\model\OrderLog();
            if( !$orderLogModel->markLog($user_id,$order_goods_info['order_id'],$log_msg) ){
                $code = 000016;
                throw new Exception('下单记录失败');
            }
            $returnLogModel = new \app\order\model\OrderReturnLog();
            if (!$returnLogModel->markLog($user_id, $return_id, '用户申请'.$this->model::$map_return_type[$return_type]['desc'], $returnLogModel::OPERATOR_TYPE_USER)) {
                $code = 020211;
                throw new Exception('日志记录失败');
            }
            $this->model->commit();
        }catch( Exception $e ){
            $this->model->rollback();
            return errorJson($code, $e->getMessage());
        }
        return successJson($return_id,'申请成功');
    }

    //退换商家审核
    public function returnVerify(){
        $code = 020201;
        if( !$param = $this->validate->returnVerify(input('post.')) ) {
            return errorJson($code, $this->validate->getError());
        }
        $this->model->startTrans();
        try {
            $where['id'] = $param['return_id'];
            //获取退换订单信息
            $returnInfo = $this->model->where($where)->find();
            if ( empty($returnInfo) ) {
                $code = 020210;
                throw new Exception('退换单信息获取失败');
            }
            $updateData = [];
            $updateData['verify'] = 1;
            $updateData['update_time'] = date('Y-m-d H:i:s');
            if (!$this->model->where($where)->update($updateData)) {
                $code = 020211;
                throw new Exception('信息更新失败');
            }
            $admin_id = 1;
            $returnLogModel = new \app\order\model\OrderReturnLog();
            if (!$returnLogModel->markLog($admin_id, $param['return_id'], '退换货单商家审核同意', $returnLogModel::OPERATOR_TYPE_ADMIN)) {
                $code = 020211;
                throw new Exception('日志记录失败');
            }
            actionLogs('商家审核退换货单id：' . $param['return_id'] . ' 已收货', $this->model);
            $this->model->commit();
        }catch( Exception $e ){
            $this->model->rollback();
            return errorJson($code, $e->getMessage());
        }
        return successJson('success','审核成功');
    }

    //退换商家确认收到商品
    public function returnShopGetGoods(){
        $code = 020201;
        if( !$param = $this->validate->returnShopGetGoods(input('post.')) ) {
            return errorJson(020201, $this->validate->getError());
        }
        $this->model->startTrans();
        try{
            $where['id'] = $param['return_id'];
            //获取退换订单信息
            $returnInfo = $this->model->where($where)->find();
            if( empty($returnInfo) ){
                $code = 020210;
                throw new Exception('退换单信息获取失败');
            }
            //获取orderInfo
            $orderModel = new \app\order\model\Order();
            $orderGoodsModel = new \app\order\model\OrderGoods();
            $orderGoodsInfo = $orderGoodsModel->alias('og')
                ->field('og.*,o.order_sn,o.shop_id')
                ->join($orderModel->getTable().' o','o.order_id = og.order_id')
                ->where('og.id',$returnInfo['order_goods_id'])
                ->find();
            $updateData = [];
            $updateData['goods_back_status'] = 2;
            $updateData['goods_back_time'] = date('Y-m-d H:i:s');
            $updateData['update_time'] = $updateData['goods_back_time'];
            if( !$this->model->where($where)->update($updateData) ){
                $code = 020211;
                throw new Exception('信息更新失败');
            }
            //商品入库
            $shopDepotModel = new \app\depot\model\ShopDepot();
            $shopDepotChangeModel = new \app\depot\model\ShopDepotChange();
            $shop_depot_change = $shopDepotChangeModel::TYPE_SALES_SWAP_IN;
            if($returnInfo['return_type'] == $this->model::RETURN_TYPE_REFUND){
                $shop_depot_change = $shopDepotChangeModel::TYPE_SALES_RETURN;
            }
            $erp_info = explode(',',$orderGoodsInfo['erp_code']);
            if( !$shopDepotModel->SaleInventoryOperation($orderGoodsInfo['order_sn'],$shop_depot_change ,$orderGoodsInfo['shop_id'] , $erp_info[0],$erp_info[1],$returnInfo['return_num']) ) {
                $code = 020212;
                throw new Exception('商品入库失败');
            }
            $admin_id = 1;
            $returnLogModel = new \app\order\model\OrderReturnLog();
            if( !$returnLogModel->markLog($admin_id,$param['return_id'],'寄回商品商家确认收货',$returnLogModel::OPERATOR_TYPE_ADMIN) ) {
                $code = 020213;
                throw new Exception('日志记录失败');
            }
            actionLogs('商家审核退换货单id：'.$param['return_id'].' 已收货',$this->model);
            $this->model->commit();
        }catch( Exception $e ){
            $this->model->rollback();
            return errorJson($code,$e->getMessage());
        }
        return successJson('success','确认收货成功');
    }

    //生成换货单
    public function createReturnGoodsOrder(){
        $code = 020201;
        if( !$param = $this->validate->createReturnGoodsOrder(input('post.')) ){
            return errorJson($code,$this->validate->getError());
        }
        $where['id'] = $param['return_id'];
        $this->model->startTrans();
        try{
            //获取退换订单信息
            $returnInfo = $this->model->where($where)->find();
            if( empty($returnInfo) ){
                $code = 020210;
                throw new Exception('退换单信息获取失败');
            }
            if( $returnInfo['status'] == 1 ){
                $code = 020211;
                throw new Exception('创建失败，退换单流程已完成');
            }
            if( $returnInfo['return_type'] != $this->model::RETURN_TYPE_CHANGE ){
                $code = 020212;
                throw new Exception('只有换货类型呢才能创建');
            }
            $orderModel = new \app\order\model\Order();
            $orderGoodsModel = new \app\order\model\OrderGoods();
            //不连表查
            $goodsWhere['id'] = $returnInfo['order_goods_id'];
            $orderGoodsInfo = $orderGoodsModel->where($goodsWhere)->find();
            if( empty($orderGoodsInfo) ){
                $code = 020213;
                throw new Exception('商品订单信息获取失败');
            }
            //获取商家订单信息
            $orderInfo = $orderModel->field('order_id,order_sn,shop_id')
                ->where('order_id',$orderGoodsInfo['order_id'])
                ->find();
            if( empty($orderInfo) ){
                $code = 020213;
                throw new Exception('商品订单信息获取失败');
            }
            //创建新的商品订单
            $newData = $orderGoodsInfo->toArray();
            unset($newData['id']);
            $newData['num'] = $returnInfo['return_num'];
            $newData['status'] = 0;
            $newData['ship_status'] = 0;
            $newData['create_time'] = date('Y-m-d H:i:s');
            $newData['update_time'] = $newData['create_time'];
            if( !$orderGoodsModel->insert($newData) ){
                $code = 020214;
                throw new Exception('创建订单失败');
            }
            //出库
            $shopDepotModel = new \app\depot\model\ShopDepot();
            $shopDepotChangeModel = new \app\depot\model\ShopDepotChange();
            $erp_info = explode(',',$orderGoodsInfo['erp_code']);
            if( !$shopDepotModel->SaleInventoryOperation($orderInfo['order_sn'],$shopDepotChangeModel::TYPE_SALES_SWAP_OUT ,$orderInfo['shop_id'] , $erp_info[0],$erp_info[1],$returnInfo['return_num']) ) {
                return errorJson(020211,'商品出库失败');
            }
            //创建作废商品订单
            if( $orderGoodsInfo['num'] == $returnInfo['return_num'] ){
                $updateData['status'] = 40;
                $newData['ship_status'] = 1;
                $updateData['update_time'] = $newData['create_time'];
                if( !$orderGoodsModel->where($goodsWhere)->update($updateData) ){
                    $code = 020215;
                    throw new Exception('商品订单信息更新失败');
                }
            }else{
                //创建作废商品
                $invalidData = $newData;
                $invalidData['status'] = 40;
                $invalidData['ship_status'] = 1;
                if( !$orderGoodsModel->insert($invalidData) ){
                    $code = 020215;
                    throw new Exception('原商品单作废失败');
                }
                //更新商品订单信息
                //检查是否还有其他的退换申请
                $updateData['status'] = 0;
                $updateData['num'] = $orderGoodsInfo['num']-$returnInfo['return_num'];
                $updateData['update_time'] = $newData['create_time'];
                if( !$orderGoodsModel->where($goodsWhere)->update($updateData) ){
                    $code = 020216;
                    throw new Exception('商品订单信息更新失败');
                }
            }
            //更新退换单状态
            $returnUpdateData = [];
            $returnUpdateData['status'] = 1;
            $returnUpdataData['update_time'] = $newData['create_time'];
            if( !$this->model->where($where)->update($returnUpdateData) ){
                $code = 020216;
                throw new Exception('换货申请单信息更新失败');
            }
            //订单发货状态更新
            $updateOrderData = [];
            $updateOrderData['shipping_status'] = 0;
            $updateOrderData['finished_status'] = 0;
            $updateOrderData['update_time'] = $returnUpdataData['update_time'];
            if( !$orderModel->where('order_id',$orderInfo['order_id'])->update($updateOrderData) ){
                throw new Exception('订单物流状态更新失败');
            }
            //订单日志记录
            $returnLogModel = new \app\order\model\OrderReturnLog();
            $admin_id = 0;
            if( !$returnLogModel->markLog($admin_id,$param['return_id'],'创建换货商品单，完成换货申请',$returnLogModel::OPERATOR_TYPE_ADMIN) ) {
                $code = 020217;
                throw new Exception('日志记录失败');
            }
            $orderLogModel = new \app\order\model\OrderLog();
            if( !$orderLogModel->markLog($admin_id,$orderInfo['order_id'],'作废已换商品，创建新商品单',$orderLogModel::OPERATOR_TYPE_ADMIN) ){
                $code = 020217;
                throw new Exception('订单记录失败');
            }
            $this->model->commit();
        }catch( Exception $e ){
            $this->model->rollback();
            return errorJson($code,$e->getMessage());
        }
        //更新旧的商品订单状态
        return successJson('success','创建成功');
    }

    //用户寄回商品
    public function sand_goods_back(){
        $code = 000001;
        if( !$param = $this->validate->sand_goods_back(input('post.')) ) {
            return errorJson($code, $this->validate->getError());
        }
        $this->model->startTrans();
        try{
            //检查退换单
            $where['id'] = $param['return_id'];
            $returnInfo = $this->model->where($where)->find();
            if( empty($returnInfo) ){
                $code = 000010;
                throw new Exception('退换单信息获取失败');
            }
            if( $returnInfo['verify'] != 1){
                $code = 000011;
                throw new Exception('退货审核未通过');
            }
            if( $returnInfo['goods_back_status'] == 1 ){
                $code = 000012;
                throw new Exception('商品已提交寄回单');
            }
            $updateData = [];
            //物流公司名
            $updateData['return_back_express'] = $param['return_back_express'];
            //物流单号
            $updateData['return_back_express_sn'] = $param['return_back_express_sn'];
            $updateData['return_back_desc'] = trim($param['remark']);
            $updateData['goods_back_status'] = 1;
            $updateData['goods_back_time'] = date('Y-m-d H:i:s');
            $updateData['update_time'] = $updateData['goods_back_time'];
            if( !$this->model->where($where)->update($updateData) ){
                $code = 000013;
                throw new Exception('网络错误，处理失败');
            }
            $returnLogModel = new \app\order\model\OrderReturnLog();
            if( !$returnLogModel->markLog($param['user_id'],$param['return_id'],'用户寄回商品',$returnLogModel::OPERATOR_TYPE_USER) ) {
                $code = 000014;
                throw new Exception('信息记录失败');
            }
            $this->model->commit();
        }catch( Exception $e ){
            $this->model->rollback();
            return errorJson($code,$e->getMessage());
        }
        return successJson('success','提交成功');
    }

    //审核退款
    public function payBackVerify(){
        $code = 000001;
        if( !$param = $this->validate->payBackVerify(input('post.')) ) {
            return errorJson($code, $this->validate->getError());
        }
        $this->model->startTrans();
        try{
            //检查退换单
            $where['id'] = $param['return_id'];
            $returnInfo = $this->model->where($where)->find();
            if( empty($returnInfo) ){
                $code = 000010;
                throw new Exception('退货单信息获取失败');
            }
            if( $returnInfo['verify'] != 1){
                $code = 000011;
                throw new Exception('退货审核未通过');
            }
            if( $returnInfo['goods_back_status'] == 1 ){
                $code = 000012;
                throw new Exception('商品已提交寄回单');
            }
            if( $returnInfo['status'] == 1 ){
                $code = 020212;
                throw new Exception('审核失败，退货单流程已完成');
            }
            if( $returnInfo['return_type'] != $this->model::RETURN_TYPE_REFUND ){
                $code = 020213;
                throw new Exception('申请类型不允许此操作');
            }
            //获取商品订单信息
            $orderModel = new \app\order\model\Order();
            $orderGoodsModel = new \app\order\model\OrderGoods();
            //更新订单信息
            //创建作废商品订单
            $t = date('Y-m-d H:i:s');
            $orderGoodsWhere['id'] = $returnInfo['order_goods_id'];
            //不连表查
            $orderGoodsInfo = $orderGoodsModel->where($orderGoodsWhere)->find();
            if( empty($orderGoodsInfo) ){
                $code = 020214;
                throw new Exception('商品订单信息获取失败');
            }
            //获取商家订单信息
            $orderInfo = $orderModel->field('order_id,order_sn,shop_id')
                ->where('order_id',$orderGoodsInfo['order_id'])
                ->find();
            if( empty($orderInfo) ){
                $code = 020214;
                throw new Exception('商品订单信息获取失败');
            }
            //根据订单金额退回 start---------------
            //这里财务退款
            //根据订单金额退回 end---------------
            if( $orderGoodsInfo['num'] == $returnInfo['return_num'] ){
                $updateData['status'] = 30;
                $updateData['update_time'] = $t;
                if( !$orderGoodsModel->where($orderGoodsWhere)->update($updateData) ){
                    $code = 020215;
                    throw new Exception('商品订单信息更新失败');
                }
            }else{
                //创建已退商品
                $invalidData = $orderGoodsInfo;
                unset($invalidData['id']);
                $invalidData['num'] = $returnInfo['return_num'];
                $invalidData['status'] = 30;
                $invalidData['create_time'] = date('Y-m-d H:i:s');
                $invalidData['update_time'] = $invalidData['create_time'];
                if( !$orderGoodsModel->insert($invalidData) ){
                    $code = 020215;
                    throw new Exception('原商品单作废失败');
                }
                //更新商品订单信息
                //检查是否还有其他的退换申请
                $updateData['status'] = 0;
                $updateData['num'] = $orderGoodsInfo['num']-$returnInfo['return_num'];
                $updateData['update_time'] = $invalidData['create_time'];
                if( !$orderGoodsModel->where($orderGoodsWhere)->update($updateData) ){
                    $code = 020216;
                    throw new Exception('商品订单信息更新失败');
                }
            }
            //更新退换订单状态
            $updateData['return_pay_status'] = $param['verify'];
            $updateData['return_pay_date'] = date('Y-m-d H:i:s');
            $updateData['status'] = 1;
            if( !$this->model->where($where)->update($updateData)){
                $code = 020215;
                throw new Exception('更新申请单信息失败');
            }
            //订单日志记录
            $returnLogModel = new \app\order\model\OrderReturnLog();
            $admin_id = 0;
            if( !$returnLogModel->markLog($admin_id,$param['return_id'],'财务审核退款，完成退货申请',$returnLogModel::OPERATOR_TYPE_ADMIN) ) {
                $code = 020216;
                throw new Exception('日志记录失败');
            }
            $orderLogModel = new \app\order\model\OrderLog();
            if( !$orderLogModel->markLog($admin_id,$orderInfo['order_id'],'已退款商品申请已完成，状态更变',$orderLogModel::OPERATOR_TYPE_ADMIN) ){
                $code = 020216;
                throw new Exception('订单记录失败');
            }
            //检查订单状态是否需要更变
            if( !$orderModel->checkOrderStatus($orderInfo['order_id']) ){
                $code = 020117;
                throw new Exception($orderModel->getError());
            }
            $this->model->commit();
        }catch( Exception $e ){
            $this->model->rollback();
            return errorJson($code,$e->getMessage());
        }
        return successJson('success','退款审核成功');
    }

}
