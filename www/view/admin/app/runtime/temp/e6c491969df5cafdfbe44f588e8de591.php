<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"E:\project\www\view\admin\app\public/../application//share/view/share/list.html";i:1569397678;s:83:"E:\project\www\view\admin\app\public/../application//common/view/common/layout.html";i:1551585675;s:45:"../application/common/view/common/header.html";i:1551585675;s:45:"../application/common/view/common/footer.html";i:1546914855;}*/ ?>
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

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form" action="" id="form" >
                <input type="hidden" name="state" value="<?=isset($_GET['state']) ? $_GET['state'] : '';?>">
                <input type="hidden" name="delete" value="<?=isset($_GET['delete']) ? $_GET['delete'] : '';?>">
                <div class="layui-form-item">
                    <label class="layui-form-label">会员id</label>
                    <div class="layui-input-inline">
                        <input type="text" name="user_id" value="" placeholder="会员id" autocomplete="off" class="layui-input">
                    </div>
                    <label class="layui-form-label">审核状态</label>
                    <div class="layui-input-inline">
                        <select name="verify" >
                            <option value="" >全部</option>
                            <option value="0" >待审核</option>
                            <option value="1" >通过</option>
                            <option value="2" >不通过</option>
                        </select>
                    </div>
                    <label class="layui-form-label">精选</label>
                    <div class="layui-input-inline">
                        <select name="is_chosen" >
                            <option value="" >全部</option>
                            <option value="0" >否</option>
                            <option value="1" >是</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">确认</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
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
            <table id="share" lay-filter="test"></table>
        </div>
    </div>
</div>
<!-- 操作模板 -->
<script type="text/html" id="ctrlTpl">
    <button class="layui-btn layui-btn-xs" style="margin:0;" lay-event="detail" >详情</button>
    {{# if( d.verify == 1 ){ }}
    <button class="layui-btn layui-btn-xs layui-btn-danger" style="margin:0;" lay-event="chosen" >
    {{# if( d.is_chosen == 1 ){ }}
        取消精选
    {{# }else{ }}
        设为精选
    {{# } }}
    </button>
    {{# } }}
</script>
<script>
    var verify = getUrlParam('verify');
    var param = '/?';
    if( verify != null ){
        param += '&verify='+verify;
    }

    layui.config({
        base: '/static/layui/module/layui-soul-table/ext/'
    }).extend({
        treetable: 'soulTable'
    }).use(['layer', 'table', 'soulTable'], function () {
        var $ = layui.jquery;
        var table = layui.table,
        layer = layui.layer,
        soulTable = layui.soulTable;

    var table = layui.table;
    //执行渲染
    table.render({
        id:'share'
        ,elem: '#share' //
        ,height: 'auto' //容器高度
        ,url: '/goods/category/getCateAll/?showType=list'
        ,page: true
        ,headers: {
            ctrl: SHOP_DATA
        },
        totalRow:true,
        contextmenu: {
            // 表头右键菜单配置
            header: [
                {
                    name: '复制',
                    icon: 'layui-icon layui-icon-template',
                    click: function(obj) {
                        layer.msg('复制操作自行处理。待复制内容: <span class="layui-red">' + obj.text + '</span>') 
                    }
                },
                {
                    name: '导出excel',
                    click: function() {
                      soulTable.export(this.id)
                    }
                },
                {
                    name: '重载表格',
                    icon: 'layui-icon layui-icon-refresh-1',
                    click: function() {
                        table.reload(this.id)
                    }
                },
                {
                    name: '字体颜色',
                    icon: 'layui-icon layui-icon-theme',
                    children: [
                        {
                             name: '红色',
                             children: [
                                 {
                                    name: '大红色',
                                    click: function(obj) {
                                      obj.elem.css('color', 'red')
                                    }
                                 },
                                 {
                                    name: '粉红色',
                                    click: function(obj) {
                                      obj.elem.css('color', 'pink')
                                    }
                                 },
                             ]
                        },
                        {
                             name: '绿色',
                             click: function(obj) {
                               obj.elem.css('color', '#009688')
                             }
                         }
                     ] 
                }
                ,
                {
                    name: '背景色',
                    icon: 'layui-icon layui-icon-theme',
                    children: [
                        {
                             name: '蓝色',
                             click: function(obj) {
                                obj.elem.css('background', '#01AAED')
                             }
                        },
                        {
                             name: '黄色',
                             click: function(obj) {
                               obj.elem.css('background', '#FFB800')
                             }
                         }
                     ] 
                }
            ],
            // 表格内容右键菜单配置
            body: [
                {
                    name: '删除当前行数据',
                    click: function(obj) {
                        obj.del() 
                    }
                },
                {
                    name: '更新数据',
                    click: function(obj) {
                        obj.update({author: obj.row.author + '+1'})
                    }
                },
                {
                    name: '行-蓝色',
                    click: function(obj) {
                        obj.trElem.css('background', '#01AAED')
                    }
                },
                {
                    name: '红色',
                    click: function(obj) {
                      obj.elem.css('color', 'red')
                    }
                }
            ],
            // 合计栏右键菜单配置
            total: [
                {
                    name: '背景黄色',
                    click: function(obj) {
                      obj.elem.css('background', '#FFB800')
                    }
                }
            ] 
        }
        ,cols: [[
            {field: 'id', title: 'id', width:80}
            // ,{field: 'title', title: '标题'}
            // ,{field: 'click', title: '点击量', width:80}
            // ,{field: 'laud', title: '点赞数', width:80}
            // ,{field: 'ticket', title: '投票数', width:80}
            // ,{field: 'verify_desc', title: '审核状态', width:80}
            // ,{field: 'create_time', title: '发出时间', width:180}
            // ,{field: 'id', title: '操作', width:200, templet: '#ctrlTpl'}
        ]],
        done:function(){
            soulTable.render(this);
        }
    });

    var form = layui.form;
    layui.use('form', function(){
        form.on('submit', function(data){
            searchData = data.field;
            //上述方法等价于
            table.reload('share', {
                where: searchData,
                page: {
                    curr: 1 //重新从第 1 页开始
                }
            });
            return false;
        });
        form.render();
    });

    table.on('tool(test)', function(obj) {
        var data = obj.data;
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr; //获得当前行 tr 的DOM对象
        switch (layEvent) {
            case 'detail' ://查看订单详情
                layer.open({
                    title:'详情',
                    type:2,
                    shadeClose: true,
                    closeBtn:1,
                    area:['90%','90%'],
                    content:"/share/share/detail.html?id="+data.id
                });
                break;
            case 'chosen' :
                layer.confirm('是否确认精选操作', {
                    btn: ["确定","取消"] //按钮
                }, function(){
                    var param = {};
                    param.id = data.id;
                    param.is_chosen = data.is_chosen ? 0 : 1;
                    request.setHost(SHOP_DATA).post('/share/share/edit',param,function(res){
                        if( res.code == 0 ){
                            // 成功提示
                            layer.msg(res.msg);
                            setTimeout(function(){
                                table.reload('share');
                            },1000);
                        } else {
                            // 错误提示
                            layer.msg(res.msg);
                        }
                    });
                });
                break;
            default :
                break;
        }
    });

    function callback(){
        table.reload('share');
        layer.closeAll();
    }
});
</script>

</body>
</html>