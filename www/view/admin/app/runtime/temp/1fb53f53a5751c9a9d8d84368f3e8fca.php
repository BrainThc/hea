<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:82:"E:\project\www\view\admin\app\public/../application//picshow/view/qrcode/list.html";i:1548407806;s:83:"E:\project\www\view\admin\app\public/../application//common/view/common/layout.html";i:1551585675;s:45:"../application/common/view/common/header.html";i:1551585675;s:45:"../application/common/view/common/footer.html";i:1546914855;}*/ ?>
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

<div class="layui-fluid" id="app">
	<div class="layui-card">
		<div class="layui-card-body">
			<table id="myDataTable" lay-filter="myDataTable"></table>
		</div>
	</div>
</div>

<!-- 操作模板 -->
<script type="text/html" id="ctrlTpl">
	<button class="layui-btn layui-btn-xs" lay-event="qrcode">二唯码</button>
	<button class="layui-btn layui-btn-xs" lay-event="edit">修改</button>
	<button class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</button>
</script>
<script type="text/html" id="toolbarTpl">
	<button class="layui-btn layui-btn-sm" lay-event="add">生成普通二唯码</button>
	<button class="layui-btn layui-btn-sm" lay-event="add_weapp">生成小程序码</button>
</script>

<script type="text/javascript" charset="utf-8" src="/static/js/picshow/qrcode.js"></script>
</body>
</html>