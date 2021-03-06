<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"E:\project\www\view\admin\app\public/../application//system/view/delivery/detail.html";i:1551856866;s:83:"E:\project\www\view\admin\app\public/../application//common/view/common/layout.html";i:1551585675;s:45:"../application/common/view/common/header.html";i:1551585675;s:45:"../application/common/view/common/footer.html";i:1546914855;}*/ ?>
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

<div class="p-1" id="app">
	<form class="layui-form layui-form-pane" id="openAddForm" lay-filter="layer">
		<div class="layui-form-item">
			<label class="layui-form-label">配送标题</label>
			<div class="layui-input-block">
				<input type="text" name="delivery_name" placeholder="运费模板名称"  class="layui-input" required lay-verify="required" v-model="row.delivery_name">
			</div>
		</div>
    <div class="layui-form-item" lay-filter="deliveryCode">
      <label class="layui-form-label">配送代码</label>
      <div class="layui-input-block">
        <select name="delivery_code" required lay-verify="required">
          <option value="">选择物流公司代码</option>
          <option v-for="(item, key) in express" :key="key" :value="item.code" v-bind:selected="item.code==row.delivery_code">{{item.name}} [{{item.code}}]</option>
        </select>
      </div>
    </div>
    <div class="layui-form-item" lay-filter="shopId">
      <label class="layui-form-label">所属店铺</label>
      <div class="layui-input-block">
        <select name="shop_id" required lay-verify="required">
          <option value="">选择店铺</option>
          <option v-for="(item, key) in shops" :key="key" :value="item.id" v-bind:selected="item.id==row.shop_id">{{item.name}}</option>
        </select>
      </div>
    </div>
    <div class="layui-form-item bg-white" pane>
      <label class="layui-form-label">默认模板</label>
      <div class="layui-input-block">
        <input type="radio" name="is_default" value="1" title="是" v-bind:checked="row.is_default==1">
        <input type="radio" name="is_default" value="0" title="否" v-bind:checked="row.is_default==0">
      </div>
    </div>
    <div class="layui-form-item bg-white" pane>
      <label class="layui-form-label">状态</label>
      <div class="layui-input-block">
        <input type="radio" name="status" value="1" title="启用" v-bind:checked="row.status==1">
        <input type="radio" name="status" value="0" title="禁用" v-bind:checked="row.status==0">
      </div>
    </div>
    <div class="layui-form-item bg-white" pane>
      <label class="layui-form-label">货到付款</label>
      <div class="layui-input-block">
        <input type="radio" name="after_pay" value="1" title="支持" v-bind:checked="row.after_pay==1">
        <input type="radio" name="after_pay" value="0" title="不支持" v-bind:checked="row.after_pay==0">
      </div>
    </div>
    <div class="layui-form-item bg-white" pane>
      <label class="layui-form-label">运费到付</label>
      <div class="layui-input-block">
        <input type="radio" name="after_fee" value="1" title="支持" v-bind:checked="row.after_fee==1">
        <input type="radio" name="after_fee" value="0" title="不支持" v-bind:checked="row.after_fee==0">
      </div>
    </div>
		<div class="layui-form-item">
			<label class="layui-form-label">配送说明</label>
			<div class="layui-input-block">
				<textarea name="delivery_desc" placeholder="运费模板配送说明" class="layui-textarea" v-model="row.delivery_desc"></textarea>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<input type="hidden" name="id" v-if="id" v-model="id">
				<button class="layui-btn" lay-submit lay-filter="edit-layer">立即提交</button>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript" charset="utf-8" src="/static/js/vue.js"></script>
<script type="text/javascript">
const id = getUrlParam('id')
var app = new Vue({
  el: '#app',
  data: {
  	id: '',
    row: {},
    shops: [],
    express: []
  },
  mounted: function () {
  	if (id && id != 0) this.getOne()
    this.getShops()
    this.getExpress()
  },
  methods: {
  	// 获取数据
  	getOne: function() {
      let _this = this
      _this.id = id
      request.setHost('shop_data').get('/system/delivery/one?id='+id, function (json) {
        if (json.code === 0) {
          _this.row = json.data
          _this.$nextTick().then (function () {
            layui.form.render()
          })
        } else {
          layer.alert(json.msg)
        }
      })
    },
    // 查询店铺列表
    getShops: function() {
      let _this = this
      _this.id = id
      request.setHost('center_data').get('/merchant/shop/all', function (json) {
        if (json.code === 0) {
          _this.shops = json.data
          _this.$nextTick().then (function () {
            layui.form.render('select')
          })
        } else {
          layer.alert(json.msg)
        }
      })
    },
    // 查询物流公司列表
    getExpress: function() {
  		let _this = this
  		_this.id = id
  		request.setHost('shop_data').get('/system/express/all', function (json) {
  			if (json.code === 0) {
          _this.express = json.data
          _this.$nextTick().then (function () {
            layui.form.render('select')
          })
  			} else {
  				layer.alert(json.msg)
  			}
  		})
  	}
  }
})

// layui
layui.use('form', function () {
  const form = layui.form
  form.render()

  //监听添加层提交
  form.on('submit(edit-layer)', function (data) {
  	const loadLayer = layer.load()
  	let url = '/system/delivery/add'
  	if (id && id !=0){
  		url = '/system/delivery/edit'
  	}
    request.setHost('shop_data').post(url, data.field, function (json){
    	if (json.code === 0) {
    		layer.msg(json.msg, {
    			icon: 1,
    			time: 2000
    		}, function () {
    			// 刷新父页面
    			parent.location.reload()
    		})
    	}else{
	    	layer.close(loadLayer)
    		layer.alert(json.msg, function (index) {
    			layer.close(index)
    		})
    	}
    })
    return false
  })

})
</script>
</body>
</html>