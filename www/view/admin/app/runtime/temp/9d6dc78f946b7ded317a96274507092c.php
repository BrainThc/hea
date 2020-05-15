<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:81:"E:\project\www\view\admin\app\public/../application//order/view/order/detail.html";i:1555229783;s:83:"E:\project\www\view\admin\app\public/../application//common/view/common/layout.html";i:1551585675;s:45:"../application/common/view/common/header.html";i:1551585675;s:45:"../application/common/view/common/footer.html";i:1546914855;}*/ ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>25BOY 新零售系统v3</title>
<link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
<link rel="stylesheet" href="/static/style/common.css" media="all">
<link rel="stylesheet" href="/static/style/admin.css" media="all">
<script src="/static/js/jquery-3.1.1.min.js"></script>

<!-- 百度echarts -->
<script src="/static/js/echarts.min.js"></script>

<!-- 自定义js -->
<script src="/static/js/common.js"></script>
<script src="/static/js/request.js"></script>

<!-- layui组件js -->
<!-- <script src="/static/layui/layui.js"></script> -->
<script src="/static/layui/layui.all.js"></script>

<script src="/static/js/layui-common.js"></script>
<!-- 全局参数 -->
<script type="text/javascript">
const photo_space_token = "<?php echo \think\Session::get('photojwttoken'); ?>"
const photo_handle_url = "<?php echo url('/handlePhoto.html','','',true);?>"
</script>
</head>

<style>
    .body-tab th{ min-width:100px; padding:5px 0; line-height:35px; text-align:right; }
    .body-tab td{ min-width:100px; padding:5px 0; }
    [v-cloak]{display:none;}
</style>
<div class="layui-fluid" id="vue_main"  v-cloak>
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">综合信息</li>
                    <li v-if="discount_list.length > 0">优惠信息</li>
                    <li>商品信息</li>
                    <li>物流包裹</li>
                    <li>订单日志</li>
                </ul>
                <div class="layui-tab-content" >
                    <div class="layui-tab-item layui-show">
                        <div class="layui-card">
                            <div class="layui-card-header">基础信息</div>
                            <table class="body-tab">
                                <tr>
                                    <th>店铺：</th>
                                    <td>{{ shop_info.name }}</td>
                                    <th>发货方式：</th>
                                    <td style="color:#FB5A5C;" >{{ order_info.orderTypeDesc }}</td>
                                </tr>
                                <tr>
                                    <th>订单号：</th>
                                    <td>{{ order_info.order_sn }}</td>
                                    <th>订单状态：</th>
                                    <td style="color:#FB5A5C;" >{{ order_info.orderStatusDesc }}</td>
                                    <th>支付类型：</th>
                                    <td style="color:#FB5A5C;" >{{ order_info.pay_status ? '未关联支付类型' : '未支付' }}</td>
                                </tr>
                                <tr>
                                    <th>会员id：</th>
                                    <td>{{ user_info.id }}</td>
                                    <th>会员名：</th>
                                    <td>{{ user_info.user_name }}</td>
                                    <th>会员手机：</th>
                                    <td>{{ user_info.phone }}</td>
                                    <th>下单时间：</th>
                                    <td>{{ order_info.create_time }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="layui-card">
                            <div class="layui-card-header">订单计算</div>
                            <table class="body-tab">
                                <tr>
                                    <th style="color:#FB5A5C;">订单总额：</th>
                                    <td style="color:#FB5A5C;" >{{ order_info.order_price }}</td>
                                    <th style="color:#FB5A5C;">已实付金额：</th>
                                    <td style="color:#FB5A5C;" >{{ order_info.pay_price }}</td>
                                    <th style="color:#01AAED;">商品总额：</th>
                                    <td style="color:#01AAED;">{{ order_info.goods_price }}</td>
                                    <th style="color:#01AAED;">运费：</th>
                                    <td style="color:#01AAED;">{{ order_info.ship_price }}</td>
                                </tr>
                                <tr>
                                    <th style="color:#01AAED;">积分抵扣：</th>
                                    <td style="color:#01AAED;">{{ order_info.point_price }}</td>
                                    <th style="color:#01AAED;">优惠券优惠：</th>
                                    <td style="color:#01AAED;">{{ order_info.coupon_price }}</td>
                                    <th style="color:#01AAED;">积分抵扣：</th>
                                    <td style="color:#01AAED;">{{ order_info.discount_price }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="layui-card">
                            <div class="layui-card-header">收货人信息</div>
                            <table class="body-tab">
                                <table class="body-tab" style="overflow:hidden;">
                                    <tr>
                                        <th class="title">收货人：</th>
                                        <td>{{ consignee.consignee_name }}</td>
                                        <th class="title">联系电话：</th>
                                        <td>{{ consignee.mobile }}</td>
                                        <!--<td class="title">邮编：</td>-->
                                        <!--<td>{{ consignee.user_name }}</td>-->
                                        <!--<td class="title">邮编:</td>-->
                                    </tr>
                                    <tr>
                                        <th class="title">收货地址：</th>
                                        <td colspan="5">{{ consignee.prov_name+consignee.city_name+consignee.area_name+' '+consignee.address }}</td>
                                    </tr>
                                    <tr>
                                        <th>下单备注：</th>
                                        <td colspan="5" style="max-width:500px; color:#009688;">{{ order_info.remark }}</td>
                                    </tr>
                                    <tr>
                                        <th>平台备注信息：</th>
                                        <td colspan="5" style="max-width:500px; color:#009688;">
                                            <textarea placeholder="订单备注信息" class="layui-textarea" name="order_desc" v-model="order_desc" ></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <td  colspan="5"  >
                                            <button type="button" class="layui-btn" v-on:click="updateDesc" >保存</button>
                                        </td>
                                    </tr>
                                </table>
                            </table>
                        </div>
                    </div>
                    <div class="layui-tab-item" v-if="discount_list.length > 0" >
                        <table class="layui-table">
                            <colgroup>
                                <col width="150">
                                <col width="200">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>优惠主题</th>
                                <th>优惠内容</th>
                                <th>优惠金额</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(discount,d_index) in discount_list" >
                                <td>{{ discount.discount_title }}</td>
                                <td>{{ discount.discount_content }}</td>
                                <td>{{ discount.discount_price }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-tab-item">
                        <table class="layui-table" lay-filter="goods_table" >
                            <colgroup>
                                <col width="20">
                                <col width="150">
                                <col>
                                <col>
                                <col>
                                <col>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>图片</th>
                                <th>商品名</th>
                                <th>商品规格</th>
                                <th>erp货号</th>
                                <th>
                                    <span style="color:#FB5A5C;">下单单价</span>
                                    <i>/</i>
                                    <span style="color:#01AAED;" >积分</span>
                                    <i>/</i>
                                    <span style="color:#01AAED;" >优惠券</span>
                                    <i>/</i>
                                    <span style="color:#01AAED;" >活动</span>
                                    <i>/</i>
                                    <span>现价</span>
                                </th>
                                <th>数量</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(goods,index) in goods_list" >
                                <td>{{index+1}}</td>
                                <td><img :src="goods.item_info.item_img" ></td>
                                <td><span style="color:#FB5A5C;" v-if="goods.gift_id>0">（赠品）</span>{{goods.item_info.goods_name}}<span style="color:#FB5A5C;" v-if="goods.status>0">（{{goods.goods_status_desc}}）</span><span v-if="goods.packageNum > 0" style="color:#FF5722">（物流包裹{{goods.packageNum}}）</span></td>
                                <td>{{goods.item_info.pv_name}}</td>
                                <td>{{goods.item_info.erp_code}}</td>
                                <td>
                                    <span style="color:#FB5A5C;">{{goods.item_price}}</span>
                                    <i>/</i>
                                    <span style="color:#01AAED;" >{{goods.point_price}}</span>
                                    <i>/</i>
                                    <span style="color:#01AAED;" >{{goods.coupon_price}}</span>
                                    <i>/</i>
                                    <span style="color:#01AAED;" >{{goods.discount_price}}</span>
                                    <i>/</i>
                                    <span>{{goods.item_info.item_price}}</span>
                                </td>
                                <td>{{goods.num}}</td>
                                <td>
                                    <button class="layui-btn layui-btn-danger layui-btn-xs" style="margin:0;" v-on:click="cancelGoods(goods.id)" v-if="order_info.verify == 0 && goods.status == 0 && order_info.pay_status == 1" >取消商品退款</button>
                                    <button class="layui-btn layui-btn-danger layui-btn-xs" style="margin:0;" v-if="goods.ship_status == 0 && goods.status == 0" v-on:click="changeGoods(goods.id,goods.goods_item_id,goods.goods_id,shop_info.id)" >更换商品</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-tab-item">
                        <table class="layui-table" v-if="package_list.length > 0">
                            <colgroup>
                                <col width="150">
                                <col width="200">
                                <col>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>包裹号</th>
                                <th>物流公司</th>
                                <th>单号</th>
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(package,p_index) in package_list" >
                                <td>{{p_index+1}}</td>
                                <td>{{package.name}}</td>
                                <td>{{package.express_sn}}</td>
                                <td>{{package.remark}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <p style="text-align:center; margin:0 auto;" v-if="package_list.length == 0" >暂无物流信息</p>
                    </div>
                    <div class="layui-tab-item">
                        <table class="layui-table">
                            <colgroup>
                                <col width="150">
                                <col width="200">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>用户</th>
                                <th>内容</th>
                                <th>时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(log,index) in log" >
                                <td>{{ log.operator_type ? 'admin' : '会员' }}</td>
                                <td>{{ log.content }}</td>
                                <td>{{ log.create_time }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf-8" src="/static/js/vue.js"></script>
<script>
    var id = getUrlParam('id');

    var form = layui.form;
    var Vue = new Vue({
        el: '#vue_main',
        data:{
            'order_id' : id,
            'order_info' : {},
            'order_desc' : '',
            'shop_info' : {},
            'user_info' : {},
            'consignee' : {},
            'package_list' : {},
            'goods_list' : {},
            'log' : {},
            'discount_list' : {}
        },
        mounted:function(){
            var that = this;
            that.getOrderInfo();
        },
        methods:{
            getOrderInfo:function(){
                var that = this;
                var param = {};
                param.order_id = that.order_id;
                request.setHost(SHOP_DATA).post('/order/order/getOrderInfo',param, function(res){
                    if (res.code == 0) {
                        that.order_info = res.data.order_info;
                        that.order_desc = res.data.order_info.description;
                        that.shop_info = res.data.shop_info;
                        that.user_info = res.data.user_info;
                        that.consignee = res.data.consignee;
                        that.package_list = res.data.order_package;
                        that.goods_list = res.data.goods_list;
                        that.log = res.data.log;
                        that.discount_list = res.data.discount_list;
                    } else {
                        // 错误提示
                        layer.msg(res.msg);
                    }
                });
            },
            cancelGoods:function(id){
                console.log(id);
                layer.confirm('是否确认取消并退款商品', {
                    btn: ["确定","取消"] //按钮
                }, function(){
                    request.setHost(SHOP_DATA).post('/order/order_goods/cancelGoods',{id:id}, function(res){
                        if (res.code == 0) {
                            // 成功提示
                            layer.msg(res.msg);
                            setTimeout(function(){
                                location=location;
                            },1000);
                        } else {
                            // 错误提示
                            layer.msg(res.msg);
                        }
                    });
                });
            },
            changeGoods:function(order_goods_id,item_id,goods_id,shop_id){
                var toUrl = "/order/order/goods_change_list.html?order_goods_id="+order_goods_id+"&item_id="+item_id+'&goods_id='+goods_id+'&shop_id='+shop_id;
                layer.open({
                    title:'更换商品',
                    type:2,
                    shadeClose: true,
                    closeBtn:1,
                    area:['90%','90%'],
                    content:toUrl
                });
            },
            updateDesc:function(){
                var that = this;
                layer.confirm('是否确认保存备注信息', {
                    btn: ["确定","取消"] //按钮
                }, function(){
                    var param = {};
                    param.order_id = that.order_id;
                    param.description = that.order_desc;
                    request.setHost(SHOP_DATA).post('/order/order/edit',param, function(res){
                        layer.msg(res.msg);
                    });
                });
            }
        }
    });

    function callback(){
        location=location;
    }


</script>


</body>
</html>