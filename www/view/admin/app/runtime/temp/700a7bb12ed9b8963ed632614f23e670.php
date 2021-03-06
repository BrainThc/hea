<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:81:"E:\project\www\view\admin\app\public/../application//share/view/share/detail.html";i:1554715389;s:83:"E:\project\www\view\admin\app\public/../application//common/view/common/layout.html";i:1551585675;s:45:"../application/common/view/common/header.html";i:1551585675;s:45:"../application/common/view/common/footer.html";i:1546914855;}*/ ?>
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
    .body-tab tr{ padding:10px 0; min-width:100px; display:block; }
    .layui-card-header{font-weight:600;}
    .img-list{float:left; width:150px; margin-right:10px; }
    .img-list .img-box{ width:150px; height:150px; border:1px solid #ccc; position:relative; overflow:hidden;}
    .img-list img{width:150px; height:150px;}
    .img-list a{display:block; width:150px; text-align:center; }
    .mask_view{position:absolute; width:150px; height:150px; left:0; top:0; background:#000; opacity: .7;}
    [v-cloak]{display:none;}
</style>
<script src="/static/ueditor/ueditor.config.js"></script>
<script src="/static/ueditor/ueditor.all.js"></script>
<script src="/static/ueditor/lang/zh-cn/zh-cn.js"></script>
<link rel="stylesheet" href="/static/jwt/style/cj.css" media="all">
<div class="layui-fluid" id="vue_main"  v-cloak >
    <div class="layui-card">
        <form class="layui-form" id="form">
            <div class="layui-card-body">
                <div class="layui-form-item" v-if="share_info">
                    <div class="layui-input-inline" style="width:auto;" >
                        <button type="button" class="layui-btn" v-if="share_info.verify == 0" v-on:click="verify(1)" >审核通过</button>
                        <button type="button" class="layui-btn layui-btn-danger" v-if="share_info.verify == 0" v-on:click="verify(2)" >审核不通过</button>
                    </div>
                    <div class="layui-input-inline" style="width:auto;" >
                        <button class="layui-btn" lay-submit lay-filter="formDemo">保存修改</button>
                        <button type="button" class="layui-btn layui-btn-danger" v-if="share_info.verify == 1" v-on:click="chosen" >{{ share_info.is_chosen ? '取消精选' : '设为精选' }}</button>
                    </div>
                </div>
                <div class="layui-tab layui-tab-card">
                    <ul class="layui-tab-title">
                        <li class="layui-this" >详情内容</li>
                    </ul>
                    <div class="layui-tab-content" >
                        <div class="layui-tab-item layui-show" >
                            <table class="body-tab">
                                <tr>
                                    <th>会员名：</th>
                                    <td>{{user_info.user_name}}</td>
                                    <th>提交时间：</th>
                                    <td>{{share_info.create_time}}</td>
                                </tr>
                                <tr>
                                    <th>标题：</th>
                                    <td colspan="5">
                                        <input type="text" name="title" v-model="share_info.title" placeholder="标题" autocomplete="off" class="layui-input" style="width:300px;" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>描述内容：</th>
                                    <td colspan="5">
                                        <textarea name="description" placeholder="请输入内容" class="layui-textarea" style="resize:none; width:300px;" >{{share_info.description}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>图片内容：</th>
                                    <td colspan="5">
                                        <div class="img-list" v-for="(img,index) in share_info.images_list">
                                            <div class="img-box">
                                                <div class="mask_view" v-if="del_list.indexOf(index) >= 0" ></div>
                                                <img :src="img" v-on:click="showPic(img)" >
                                            </div>
                                            <a href="javascript:void(0);" v-on:click="del_img(index)" >{{ del_list.indexOf(index) >= 0 ? '恢复' : '删除' }}</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" charset="utf-8" src="/static/js/vue.js"></script>
<script>
    var id = getUrlParam('id');
    var form = layui.form;
    var Vue = new Vue({
        el: '#vue_main',
        data:{
            'id' : id,
            'share_info' : [],
            'user_info': [],
            'del_list' : [],
        },
        mounted:function(){
            var that = this;
            that.getShareInfo();
            layui.use('form', function(){
                //监听提交
                form.on('submit(formDemo)', function(data){
                    var param = data.field;
                    //图片处理
                    var upload_img = [] ;
                    for( var index in that.share_info.images_list ){
                        if( that.del_list.indexOf(parseInt(index)) < 0 ){
                            upload_img.push(that.share_info.images_list[index]);
                        }
                    }
                    param.id = that.id;
                    param.images_list = upload_img;
                    layer.confirm('是否确认修改保存，修改后不可恢复', {
                        btn: ["确定","取消"] //按钮
                    }, function(){
                        request.setHost(SHOP_DATA).post('/share/share/edit',param , function(res){
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
                        return false;
                    });
                    return false;
                });
                form.render();
            });
        },
        methods:{
            getShareInfo:function(){
                var that = this;
                request.setHost(SHOP_DATA).get('/share/share/one?id='+that.id, function(res){
                    if( res.code == 0 ){
                        that.share_info = res.data;
                        that.user_info = res.data.user_info;
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
            },
            del_img:function(index){
                var that = this;
                if( that.del_list.indexOf(index) < 0 ){
                    that.del_list.push(index);
                }else{
                    that.del_list.splice(that.del_list.indexOf(index),1);
                }
            },
            verify:function(verify){
                var that = this;
                layer.confirm('是否确认操作', {
                    btn: ["确定","取消"] //按钮
                }, function(){
                    var param = {};
                    param.id = that.id;
                    param.verify = verify;
                    request.setHost(SHOP_DATA).post('/share/share/edit',param,function(res){
                        if( res.code == 0 ){
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
            chosen:function(){
                var that = this;
                layer.confirm('是否确认精选操作', {
                    btn: ["确定","取消"] //按钮
                }, function(){
                    var param = {};
                    param.id = that.id;
                    param.is_chosen = that.share_info.is_chosen ? 0 : 1;
                    request.setHost(SHOP_DATA).post('/share/share/edit',param,function(res){
                        if( res.code == 0 ){
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
            }
        }
    });
</script>


</body>
</html>