<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:88:"E:\project\www\view\admin\app\public/../application//order/view/order_return/detail.html";i:1552879447;s:83:"E:\project\www\view\admin\app\public/../application//common/view/common/layout.html";i:1551585675;s:45:"../application/common/view/common/header.html";i:1551585675;s:45:"../application/common/view/common/footer.html";i:1546914855;}*/ ?>
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
    .body-tab th{ min-width:100px; line-height:35px; text-align:right; }
    .body-tab td{ min-width:100px; }
    .layui-card-header{font-weight:600;}
    .img-list{float:left; width:150px; border:1px solid #ccc; margin-right:10px; }
</style>
<div class="layui-fluid" id="vue_main">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="layui-this">申请信息</li>
                    <li>寄回包裹</li>
                    <li>订单日志</li>
                </ul>
                <div class="layui-tab-content" >
                    <div class="layui-tab-item layui-show">
                        <div class="layui-card">
                            <div class="layui-card-header">基础信息</div>
                            <table class="body-tab">
                                <tr>
                                    <th>会员id：</th>
                                    <td>{{user_info.id}}</td>
                                    <th>会员名：</th>
                                    <td>{{user_info.user_name}}</td>
                                    <th>会员手机：</th>
                                    <td>{{user_info.user_name}}</td>
                                </tr>
                                <tr>
                                    <th>申请单号：</th>
                                    <td>{{ return_info.order_return_sn }}</td>
                                    <th>申请状态：</th>
                                    <td>{{ return_info.return_status_desc }}</td>
                                    <th>申请时间时间：</th>
                                    <td>{{ return_info.create_time }}</td>
                                </tr>
                            </table>
                            <div class="layui-card-header">商品单信息</div>
                            <table class="body-tab">
                                <tr>
                                    <th>商品erp货号：</th>
                                    <td>{{goods_info.erp_code}}</td>
                                </tr>
                                <tr>
                                    <th>商品图：</th>
                                    <td>
                                        <div class="img-list"><img :src="goods_info.item_images" ></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>商品单价：</th>
                                    <td>{{goods_info.item_price}}</td>
                                    <th>商品数量：</th>
                                    <td>{{goods_info.num}}</td>
                                </tr>
                            </table>
                            <div class="layui-card-header">申请内容</div>
                            <table class="body-tab">
                                <tr>
                                    <th>申请原因类型：</th>
                                    <td>{{return_reason.reason_title}}</td>
                                </tr>
                                <tr>
                                    <th>申请描述：</th>
                                    <td>{{ return_info.return_content }}</td>
                                </tr>
                                <tr>
                                    <th>申请图片内容：</th>
                                    <td>
                                        <div class="img-list" v-for="(img,i_index) in return_info.return_images" v-on:click="showPic(img)" ><img :src="img" ></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="layui-tab-item">
                        <table class="layui-table" v-if="return_info.return_back_express_sn != ''">
                            <colgroup>
                                <col width="150">
                                <col width="200">
                                <col>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>物流公司</th>
                                <th>单号</th>
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tbody>
                                <td>{{return_info.return_back_express}}</td>
                                <td>{{return_info.return_back_express_sn}}</td>
                                <td>{{return_info.return_back_desc}}</td>
                            </tbody>
                        </table>
                        <p style="text-align:center; margin:0 auto;" v-if="return_info.return_back_express_sn == ''" >暂无物流信息</p>
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
                            <tr v-for="(log,index) in return_log" >
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
            'return_id' : id,
            'return_info' : [],
            'user_info' : [],
            'goods_info' : [],
            'return_reason' :[],
            'return_log' : []
        },
        mounted:function(){
            var that = this;
            that.getReturnInfo();
        },
        methods:{
            getReturnInfo:function(){
                var that = this;
                request.setHost(SHOP_DATA).get('/order/order_return/one?id='+that.return_id, function(res){
                    if (res.code == 0) {
                        that.return_info = res.data.return_info;
                        that.user_info = res.data.user_info;
                        that.goods_info = res.data.goods_info;
                        that.return_reason = res.data.return_reason;
                        that.return_log = res.data.return_log;
                    } else {
                        // 错误提示
                        layer.msg(res.msg);
                    }
                });
            },
            showPic:function(img){
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    shadeClose: true,
                    skin: 'yourclass',
                    content: '<img src="'+img+'">'
                });
            }
        }
    });
</script>


</body>
</html>