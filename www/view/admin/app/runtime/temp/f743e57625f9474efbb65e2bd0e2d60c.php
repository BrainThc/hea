<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:80:"E:\project\www\view\admin\app\public/../application//goods/view/brands/list.html";i:1553226904;s:83:"E:\project\www\view\admin\app\public/../application//common/view/common/layout.html";i:1551585675;s:45:"../application/common/view/common/header.html";i:1551585675;s:45:"../application/common/view/common/footer.html";i:1546914855;}*/ ?>
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
            <table id="brands" lay-filter="test"></table>
        </div>
    </div>
</div>
<!-- 操作模板 -->
<script type="text/html" id="toolbarTpl">
    <button class="layui-btn layui-btn-sm" lay-event="add" >添加品牌</button>
</script>
<script type="text/html" id="ctrlTpl">
    <button class="layui-btn layui-btn-xs" style="margin:0;" lay-event="detail" >详情</button>
    <button class="layui-btn layui-btn-danger layui-btn-xs" style="margin:0;" lay-event="deleted" >删除</button>
</script>

<script>
    const photoSpaceUrl = 'http://photo.25boy.com/?token='+photo_space_token+'&url='+photo_handle_url+'&showconfirm=1';
    var LayuiOpenView;
    var table = layui.table;
    //执行渲染
    table.render({
        id:'brands',
        elem: '#brands' //
        ,height: 'auto' //容器高度
        ,url: '/goods/goods_brands/index'
        ,page: true
        ,toolbar: '#toolbarTpl'
        ,headers: {
            ctrl: SHOP_DATA
        }
        ,cols: [[
            {field: 'id', title: 'ID', width:80}
            ,{field: 'brand_name', title: '品牌名'}
            ,{field: 'brand_letter', title: '开头字母', width:100}
            ,{field: 'sort', title: '排序', width:100}
            ,{field: 'id', title: '操作', width:200, templet: '#ctrlTpl'}
        ]]
    });
    //头部按钮事件
    table.on('toolbar(test)', function(obj) {
        var data = obj.data;
        var layEvent = obj.event;
        switch( layEvent ){
            case 'add' :
                layer.open({
                    title:'添加品牌',
                    type:2,
                    shadeClose: true,
                    closeBtn:1,
                    area:['40%','80%'],
                    content:'/goods/brands/add.html',
                    success:function (layero,index){
                        LayuiOpenView = window[layero.find('iframe')[0]['name']];
                    }
                });
                break;
            default :
                return false;
                break;
        }
        return false;
    });
    //列表项事件
    table.on('tool(test)', function(obj) {
        var data = obj.data;
        var layEvent = obj.event;
        switch(layEvent) {
            case 'detail' : //详情
                layer.open({
                    title:'品牌详情',
                    type:2,
                    shadeClose: true,
                    closeBtn:1,
                    area:['40%','80%'],
                    content:"/goods/brands/detail.html?id="+data.id,
                    success:function (layero,index){
                        LayuiOpenView = window[layero.find('iframe')[0]['name']];
                    }
                });
                break;
            case 'deleted' ://删除
                layer.confirm("是否确认删除！", {
                    btn: ["确定","取消"] //按钮
                }, function(){
                    request.setHost(SHOP_DATA).post('/goods/goods_brands/deleted',{id:data.id,is_deleted:1}, function(res){
                        if (res.code == 0) {
                            // 成功提示
                            layer.msg(res.msg);
                            setTimeout(function(){
                                table.reload('brands');
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
        table.reload('brands');
        layer.closeAll();
    }
</script>

</body>
</html>