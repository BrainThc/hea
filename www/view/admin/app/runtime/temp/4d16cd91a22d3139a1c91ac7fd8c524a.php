<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:84:"E:\project\www\view\admin\app\public/../application//goods/view/categorys/index.html";i:1570516647;s:83:"E:\project\www\view\admin\app\public/../application//common/view/common/layout.html";i:1551585675;s:45:"../application/common/view/common/header.html";i:1551585675;s:45:"../application/common/view/common/footer.html";i:1546914855;}*/ ?>
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
            <div>
                <button class="layui-btn layui-btn-sm layui-btn-primary" id="btn-refresh" style="display:none;">刷新表格</button>
                <table id="table1" class="layui-table" lay-filter="table1"></table>
            </div>
        </div>
    </div>
</div>
<!-- 操作模板 -->
<script type="text/html" id="toolbarTpl">
    <button class="layui-btn layui-btn-sm" lay-event="add" >添加分类</button>
    <button class="layui-btn layui-btn-sm" lay-event="btn-expand" >全部展开</button>
    <button class="layui-btn layui-btn-sm" lay-event="btn-fold">全部折叠</button>
</script>
<script type="text/html" id="ctrlTpl">
    <button class="layui-btn layui-btn-xs" style="margin:0;" lay-event="detail" >详情</button>
    <button class="layui-btn layui-btn-danger layui-btn-xs" style="margin:0;" lay-event="deleted" >{{ d.is_deleted ? '恢复' : '删除' }}</button>
</script>
<script>
    const photoSpaceUrl = 'http://photo.25boy.com/?token='+photo_space_token+'&url='+photo_handle_url+'&showconfirm=1';
    var LayuiOpenView;
    layui.config({
        base: '/static/layui/module/'
    }).extend({
        treetable: 'treetable-lay/treetable'
    }).use(['layer', 'table', 'treetable'], function () {
        var $ = layui.jquery;
        var table = layui.table;
        var layer = layui.layer;
        var treetable = layui.treetable;
        // 渲染表格
        var loadlist = function() {
            layer.load(2);
            treetable.render({
                treeColIndex: 1,
                treeSpid: 0,
                treeParentKey: 'id',
                treeChildKey: 'pid',
                elem: '#table1',
                urlType: 'get',
                url: '/goods/category/getCateAll/?showType=list',
                urlHeaders: {
                    ctrl: SHOP_DATA
                },
                toolbar: '#toolbarTpl',
                cols: [[
                    {field: 'id', title: 'id', width: 80},
                    {field: 'cate_name', title: '分类名'},
                    {field: 'sort', title: '排序', width: 80},
                    {field: 'id', title: '操作', width: 200, templet: '#ctrlTpl'}
                ]],
                done: function () {
                    layer.closeAll('loading');
                }
            });
        }
        loadlist();
        //头部按钮事件
        table.on('toolbar(table1)', function(obj) {
            var data = obj.data;
            var layEvent = obj.event;
            switch( layEvent ){
                case 'add' :
                    layer.open({
                        title:'添加分类',
                        type:2,
                        shadeClose: true,
                        closeBtn:1,
                        area:['60%','60%'],
                        content:"/goods/categorys/add.html",
                        success:function (layero,index){
                            LayuiOpenView = window[layero.find('iframe')[0]['name']];
                        }
                    });
                    break;
                case 'btn-expand' :
                    treetable.expandAll('#table1');
                    break;
                case 'btn-fold' :
                    treetable.foldAll('#table1');
                    break;
                default :
                    return false;
                    break;
            }
            return false;
        });
        //列表项事件
        table.on('tool(table1)', function(obj) {
            var data = obj.data;
            var layEvent = obj.event;
            switch(layEvent) {
                case 'detail' ://详情
                    layer.open({
                        title:'编辑分类',
                        type:2,
                        shadeClose: true,
                        closeBtn:1,
                        area:['50%','50%'],
                        content:"/goods/categorys/detail.html?id="+data.id,
                        success:function (layero,index){
                            LayuiOpenView = window[layero.find('iframe')[0]['name']];
                        }
                    });
                    break;
                case 'deleted' ://删除
                    layer.confirm("是否确认删除！删除后 商品关联分类将失效！", {
                        btn: ["确定","取消"] //按钮
                    }, function(){
                        request.setHost(SHOP_DATA).post('/goods/category/edit',{id:data.id,is_deleted:1}, function(res){
                            if (res.code == 0) {
                                // 成功提示
                                layer.msg('删除成功');
                                setTimeout(function(){
                                    $('#btn-refresh').click();
                                },1000);
                            } else {
                                // 错误提示
                                layer.msg(res.msg);
                            }
                        });
                    });
                    break;
                default :
                    return false;
                    break;
            }
            return false;
        });
        $('#btn-refresh').click(function () {
            loadlist();
        });
    });

    //打开图片空间
    var photoLayer;
    function openPhotoSpace(){
        photoLayer = layer.open({
            type: 2,
            title : '选择图片',
            content: photoSpaceUrl,
            shadeClose: true,
            area: ['80%', '80%'],
            success: function(layero){
                layer.setTop(layero);
            }
        })
    }

    /**
     * 选择图片方法
     * 从handlePhoto.html文件自动发起调用
     */
    function handlePhoto(content) {
        if(content == 'close'){
            // 关闭窗口
            layer.close(photoLayer)
        }else{
            // 业务处理
            var json = JSON.parse(content);
            if( json.length == 0 ){
                layer.msg('请选择图片');
                return false
            }
            if( json.length > 1 ){
                layer.msg('只能选择一张图片哦');
                return false
            }
            LayuiOpenView.handlePhotoBack(json[0].image);
            layer.close(photoLayer)
        }
    }

    function callback(){
        $('#btn-refresh').click();
        layer.closeAll();
    }
</script>

</body>
</html>