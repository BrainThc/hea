<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:86:"E:\project\www\view\admin\app\public/../application//order/view/order_return/list.html";i:1554631926;s:83:"E:\project\www\view\admin\app\public/../application//common/view/common/layout.html";i:1551585675;s:45:"../application/common/view/common/header.html";i:1551585675;s:45:"../application/common/view/common/footer.html";i:1546914855;}*/ ?>
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

<!--导航-->
<style>
    .layui-table-body .layui-table-cell{
        height:105px;
        line-height: 105px;
    }
    .layui-btn {
        min-width: 80px;
    }
</style>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form" action="" id="form" >
                <input type="hidden" name="state" value="<?=isset($_GET['state']) ? $_GET['state'] : '';?>">
                <input type="hidden" name="delete" value="<?=isset($_GET['delete']) ? $_GET['delete'] : '';?>">
                <div class="layui-form-item">
                    <div class="layui-input-inline">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">确认</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">申请单号</label>
                    <div class="layui-input-inline">
                        <input type="text" name="order_return_sn" value="" placeholder="退换货单号" autocomplete="off" class="layui-input">
                    </div>
                    <label class="layui-form-label">原订单号</label>
                    <div class="layui-input-inline">
                        <input type="text" name="order_sn" value="" placeholder="原购物下单号" autocomplete="off" class="layui-input">
                    </div>
                    <label class="layui-form-label">会员id</label>
                    <div class="layui-input-inline">
                        <input type="text" name="user_id" value="" placeholder="会员id" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">申请时间</label>
                    <div class="layui-input-inline">
                        <input type="text" name="start_time" id="start_time" value="" placeholder="开始时间" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid">至</div>
                    <div class="layui-input-inline">
                        <input type="text" name="end_time" id="end_time" value="" placeholder="结束时间" autocomplete="off" class="layui-input" >
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--数据列表-->
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="order_return" lay-filter="test"></table>
        </div>
    </div>
</div>
<!-- 操作模板 -->
<script type="text/html" id="statusTpl">
    <div style="line-height:50px; text-align:center;" >
        <div style="color:#fb5a5c;">{{ d.return_status_desc }}</div>
        <div>
        {{# if( d.status == 0 ) { }}
            {{# if( d.verify == 0 ) { }}
            <button class="layui-btn layui-btn-sm layui-btn-warm" style="margin:0;" lay-event="verify" >审核</button>
            {{# } }}
            {{# if( d.goods_back_status == 1 ) { }}
            <button class="layui-btn layui-btn-sm layui-btn-warm" style="margin:0;" lay-event="makeSureShip" >确认收件</button>
            {{# } }}
            {{# if( d.return_type == 1 && d.goods_back_status == 2 ) { }}
            <button class="layui-btn layui-btn-warm layui-btn-sm" style="margin:0;" lay-event="createOrderGoods" >生成换货商品单</button>
            {{# } }}
            {{# if( d.return_type == 2 && d.goods_back_status == 2 ) { }}
            <button class="layui-btn layui-btn-danger layui-btn-sm" style="margin:0;" lay-event="verifyFinance" >审核退款</button>
            {{# } }}
        {{# } }}
        </div>
    </div>
</script>
<script type="text/html" id="ctrlTpl">
    <button class="layui-btn layui-btn-xs" style="margin:0;" lay-event="detail" >详情</button>
    <button class="layui-btn layui-btn-xs" style="margin:0;" lay-event="orderDetail" >查看原订单</button>
</script>
<script>
    var order_param = '';
    var type = getUrlParam('type');
    if( type != null ){
        order_param += '&type='+type;
    }
    var verify_id = getUrlParam('verify');
    if( verify_id != null ){
        $('.layui-nav-item').removeClass('layui-this');
        order_param += '&verify='+verify_id;
        $('.layui-nav-item').eq(eval(verify_id+'+1')).addClass('layui-this');
    }

    var table = layui.table;
    var form = layui.form;
    var laydate = layui.laydate;
    layui.use('form', function(){
        form.on('submit', function(data){
            searchData = data.field;
            //上述方法等价于
            table.reload('return', {
                where: searchData,
                page: {
                    curr: 1 //重新从第 1 页开始
                }
            });
            return false;
        });
        form.render();
    });

    laydate.render({
        elem: '#start_time', //指定元素
        type: 'datetime'
    });
    laydate.render({
        elem: '#end_time', //指定元素
        type: 'datetime'
    });
    //执行渲染
    table.render({
        id : 'return',
        elem: '#order_return'
        ,height: 'auto' //容器高度
        ,url: '/order/order_return/index?'+order_param
        ,page: true
        ,headers: {
            ctrl: SHOP_DATA
        }
        ,cols: [[
            {field: 'id', title: 'ID', width:80}
            ,{field: 'order_return_sn', title: '申请单号'}
            ,{field: 'return_num', title: '数量', width:100}
            ,{field: 'return_type_desc', title: '申请类型', width:100}
            ,{field: 'id', title: '状态', width:160, templet:'#statusTpl'}
            ,{field: 'create_time', title: '申请时间', width:180}
            ,{field: 'id', title: '操作', width:200, templet: '#ctrlTpl'}
        ]]
    });

    table.on('tool(test)', function(obj){
        var data = obj.data;
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr; //获得当前行 tr 的DOM对象
        switch(layEvent){
            case 'detail' ://查看订单详情
                layer.open({
                    title:'详情',
                    type:2,
                    shadeClose: true,
                    closeBtn:1,
                    area:['90%','90%'],
                    content:"/order/order_return/detail.html?id="+data.id
                });
                break;
            case 'orderDetail' : //取消订单
                layer.open({
                    title:'订单详情',
                    type:2,
                    shadeClose: true,
                    closeBtn:1,
                    area:['90%','90%'],
                    content:"/order/order/detail.html?id="+data.order_id
                });
                break;
            case 'verify' ://审核
                var param = {};
                param.return_id = data.id;
                layer.confirm("审核", {
                    btn: ["通过","不通过"] //按钮
                }, function(){
                    param.verify = 1;
                    request.setHost(SHOP_DATA).post('/order/order_return/returnVerify',param, function(res){
                        if (res.code == 0) {
                            layer.msg(res.msg);
                            setTimeout(function(){
                                table.reload('return');
                            },1500);
                        } else {
                            // 错误提示
                            layer.msg(res.msg);
                        }
                    });
                    return false;
                },function(){
                    param.verify = 2;
                    request.setHost(SHOP_DATA).post('/order/order_return/returnVerify',param, function(res){
                        if (res.code == 0) {
                            layer.msg(res.msg);
                            setTimeout(function(){
                                table.reload('return');
                            },1500);
                        } else {
                            // 错误提示
                            layer.msg(res.msg);
                        }
                    });
                    return false;
                });
                break;
            case 'makeSureShip' : //设为已支付
                layer.confirm("是否确认收件！", {
                    btn: ["确定","取消"] //按钮
                }, function(){
                    request.setHost(SHOP_DATA).post('/order/order_return/returnShopGetGoods',{return_id:data.id}, function(res){
                        if (res.code == 0) {
                            layer.msg(res.msg);
                            setTimeout(function(){
                                table.reload('return');
                            },1500);
                        } else {
                            // 错误提示
                            layer.msg(res.msg);
                        }
                    });
                    return false;
                });
                break;
            case 'createOrderGoods' ://生成换货商品单
                layer.confirm("是否确认生成换货商品单！", {
                    btn: ["确定","取消"] //按钮
                }, function(){
                    request.setHost(SHOP_DATA).post('/order/order_return/createReturnGoodsOrder',{return_id:data.id}, function(res){
                        if (res.code == 0) {
                            layer.msg(res.msg);
                            setTimeout(function(){
                                table.reload('return');
                            },1500);
                        } else {
                            // 错误提示
                            layer.msg(res.msg);
                        }
                    });
                    return false;
                });
                break;
            case 'verifyFinance' ://设为已收货（完成订单）
                var param = {};
                param.return_id = data.id;
                layer.confirm("是否确认退款操作！", {
                    btn: ["通过","不通过"] //按钮
                }, function(){
                    param.verify = 1;
                    request.setHost(SHOP_DATA).post('/order/order_return/payBackVerify',param, function(res){
                        if (res.code == 0) {
                            layer.msg(res.msg);
                            setTimeout(function(){
                                table.reload('return');
                            },1500);
                        } else {
                            // 错误提示
                            layer.msg(res.msg);
                        }
                    });
                    return false;
                },function(){
                    param.verify = 2;
                    request.setHost(SHOP_DATA).post('/order/order_return/payBackVerify',param, function(res){
                        if (res.code == 0) {
                            layer.msg(res.msg);
                            setTimeout(function(){
                                table.reload('return');
                            },1500);
                        } else {
                            // 错误提示
                            layer.msg(res.msg);
                        }
                    });
                    return false;
                });
                break;
            default :
                return false;
                break;
        }
        return false;
    });

    function callback(){
        table.reload('return');
        layer.closeAll();
    }
</script>

</body>
</html>