<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"E:\project\www\view\admin\app\public/../application//goods/view/brands/add.html";i:1552901355;s:83:"E:\project\www\view\admin\app\public/../application//common/view/common/layout.html";i:1551585675;s:45:"../application/common/view/common/header.html";i:1551585675;s:45:"../application/common/view/common/footer.html";i:1546914855;}*/ ?>
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
    .table-box{margin-top:20px;}
</style>
<link rel="stylesheet" href="/static/jwt/style/cj.css" media="all">
<div class="layui-card">
    <div class="layui-card-body">
        <button class="layui-btn layui-btn-sm layui-btn-primary" onclick="parent.window.callback()">
            <i class="layui-icon"></i>返回
        </button>
        <form class="layui-form table-box" action="">
            <div class="layui-form-item">
                <label class="layui-form-label">品牌名</label>
                <div class="layui-input-inline">
                    <input type="text" name="brand_name" required  lay-verify="required" placeholder="请输入品牌名" autocomplete="off" class="layui-input" >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">开头字母</label>
                <div class="layui-input-inline">
                    <input type="text" name="brand_letter" required  lay-verify="required" placeholder="请输入品牌名开头字母" autocomplete="off" class="layui-input" >
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <input type="number" min="0" max="255" name="sort" required  lay-verify="required" placeholder="0-255" value="0" autocomplete="off" class="layui-input" onkeyup="checkSort(this)" >
                </div>
                <div class="layui-form-mid layui-word-aux">(排序由大都小排列)</div>
            </div>
            <div  id="upload_main" >
                <div class="layui-form-item">
                    <label class="layui-form-label">品牌LOGO<br/>(200 * 200)</label>
                    <div class="layui-input-block">
                        <div class="upload_box" style="width:130px; height:130px;" >
                            <input type="hidden" id="logo_input" name="brand_logo" class="hid-val-box" readonly />
                            <!--<div class="upload-title">品牌logo</div>-->
                            <!--<div class="upload-tips"><span>88</span> <span>*</span><span>33</span></div>-->
                            <div class="upload-view" id="logo" style="height:118px;" >
                                <img alt="" onerror="this.src='/static/jwt/images/upload_add.png'" >
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">品牌图片<br />(203 * 64)</label>
                    <div class="layui-input-block">
                        <div class="upload_box" style="width:130px; height:64px;">
                            <input type="hidden" id="img_input" name="brand_img" class="hid-val-box" readonly />
                            <div class="upload-view" id="img" style="height:50px;" >
                                <img alt="" onerror="this.src='/static/jwt/images/upload_add.png'" >
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">品牌描述</label>
                <div class="layui-input-block">
                    <textarea name="brand_desc" style="resize:none; width:300px;"  placeholder="请输入内容" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function checkSort(obj){
        var num = $(obj).val();
        if(num > 255){
            $(obj).val(255);
        }else if(num < 0){
            $(obj).val(0);
        }
    }

    let _thisObj;
    // 选择触发器
    $('#logo').click(function(){
        _thisObj = $(this);
        parent.openPhotoSpace();
    });

    $('#img').click(function(){
        _thisObj = $(this);
        parent.openPhotoSpace();
    });

    /**
     * 选择图片渲染
     * 从handlePhoto.html文件自动发起调用
     * JSON content
     */
    function handlePhotoBack(img){
        _thisObj.find('img').attr('src',img);
        $('#'+$(_thisObj).attr('id')+'_input').val(img);
    }

    var form = layui.form;
    $(document).ready(function(){
        layui.use('form', function(){
            //监听提交
            form.on('submit(formDemo)', function(data){
                request.setHost(SHOP_DATA).post('/goods/goods_brands/add', data.field, function(res){
                    if (res.code == 0) {
                        // 成功提示
                        layer.msg(res.msg);
                        setTimeout(function(){
                            parent.window.callback();
                        },1500);
                    } else {
                        // 错误提示
                        layer.msg(res.msg);
                    }
                });
                return false;
            });
        });
        form.render();
    });


</script>


</body>
</html>