<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"E:\project\www\view\admin\app\public/../application/index\view\index\index.html";i:1554801100;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>25BOY V3</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/style/admin.css" media="all">

  <script type="text/javascript" src="/static/js/jquery-3.1.1.min.js"></script>
</head>
<body class="layui-layout-body">

  <div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
      <div class="layui-header">
        <!-- 头部区域 -->
        <ul class="layui-nav layui-layout-left">
          <li class="layui-nav-item layadmin-flexible" lay-unselect>
            <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
              <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
            </a>
          </li>
          <li class="layui-nav-item" lay-unselect>
            <a id="return" href="javascript:;" layadmin-event="return" title="后退">
              <i class="layui-icon layui-icon-return"></i>
            </a>
          </li>
          <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;" layadmin-event="refresh" title="刷新">
              <i class="layui-icon layui-icon-refresh-3"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search" layadmin-event="serach" lay-action="template/search.html?keywords=">
          </li>
        </ul>
        <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

          <li class="layui-nav-item" lay-unselect>
            <a lay-href="app/message/index.html" layadmin-event="message" lay-text="消息中心">
              <i class="layui-icon layui-icon-notice"></i>

              <!-- 如果有新消息，则显示小圆点 -->
              <span class="layui-badge-dot"></span>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="http://photo.25boy.com?token=<?php echo \think\Session::get('photojwttoken'); ?>" target="_blank" lay-text="图片空间">
              <i class="layui-icon layui-icon-picture"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="note">
              <i class="layui-icon layui-icon-note"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="fullscreen">
              <i class="layui-icon layui-icon-screen-full"></i>
            </a>
          </li>
          <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;">
              <cite>管理员</cite>
            </a>
            <dl class="layui-nav-child">
              <!-- <dd><a lay-href="set/user/info.html">基本资料</a></dd> -->
              <!-- <dd><a lay-href="set/user/password.html">修改密码</a></dd> -->
              <!-- <hr> -->
              <dd id="logout" style="text-align: center;"><a>退出</a></dd>
            </dl>
          </li>

          <!-- <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="about"><i class="layui-icon layui-icon-more-vertical"></i></a>
          </li> -->
          <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
            <a href="javascript:;" layadmin-event="more"><i class="layui-icon layui-icon-more-vertical"></i></a>
          </li>
        </ul>
      </div>

      <!-- 侧边菜单 -->
      <div class="layui-side layui-side-menu">
        <div class="layui-side-scroll">
          <div class="layui-logo" lay-href="base.html">
            <span>HEA</span>
          </div>

          <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">

            <li data-name="goods" class="layui-nav-item">
              <a href="javascript:void(0);" lay-tips="商品管理" lay-direction="2">
                <i class="layui-icon layui-icon-component"></i>
                <cite>商品管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/goods/goods/list.html">商品列表</a></dd>
                <dd><a lay-href="/goods/categorys/index.html">商品分类</a></dd>
                <dd><a lay-href="/goods/brands/list.html">商品品牌</a></dd>
                <dd><a lay-href="/goods/prop/list.html">商品属性</a></dd>
                <dd><a lay-href="/goods/attribute/list.html">商品销售参数</a></dd>
                <dd><a lay-href="/goods/tag/list.html">商品标签</a></dd>
                <dd><a lay-href="/goods/evaluation/list.html">商品评论</a></dd>
              </dl>
            </li>
            <li data-name="order" class="layui-nav-item">
              <a href="javascript:void(0);" lay-tips="订单管理" lay-direction="2">
                <i class="layui-icon layui-icon-form"></i>
                <cite>订单管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/order/order/list.html">订单列表</a></dd>
                <dd><a lay-href="/order/order_return/list.html?type=1">换货订单</a></dd>
                <dd><a lay-href="/order/order_return/list.html?type=2">退货订单</a></dd>
              </dl>
            </li>
            <li data-name="goods" class="layui-nav-item">
              <a href="javascript:void(0);" lay-tips="商品管理" lay-direction="2">
                <i class="layui-icon layui-icon-carousel"></i>
                <cite>晒图管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/share/share/list.html">晒图列表</a></dd>
                <dd><a lay-href="/activity/share/list.html">晒图营销</a></dd>
              </dl>
            </li>
            <li data-name="activity" class="layui-nav-item">
              <a href="javascript:;" lay-tips="活动管理" lay-direction="2">
                <i class="layui-icon layui-icon-fire"></i>
                <cite>营销活动</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/activity/coupon/list.html">优惠券</a></dd>
                <dd><a lay-href="/activity/gift/list.html">赠品活动</a></dd>
                <dd><a lay-href="/activity/consum/o2o_month.html">店铺满减</a></dd>
                <dd><a lay-href="/activity/consum/category_sales.html">分享拼团</a></dd>
                <dd><a lay-href="/activity/consum/category_sales.html">充值送券</a></dd>
                <dd><a lay-href="/activity/consum/category_sales.html">搭配套餐</a></dd>
                <dd><a lay-href="/activity/consum/category_sales.html">搭配套餐</a></dd>
                <dd><a lay-href="/activity/consum/category_sales.html">群发短信</a></dd>
              </dl>
            </li>

            <li data-name="flow" class="layui-nav-item">
              <a href="javascript:;" lay-tips="流水管理" lay-direction="2">
                <i class="layui-icon layui-icon-water"></i>
                <!-- <i class="layui-icon layui-icon-theme"></i> -->
                <cite>流水管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/flow/consum/o2o_month.html">流水管理</a></dd>
              </dl>
            </li>

            <li data-name="user" class="layui-nav-item">
              <a href="javascript:;" lay-tips="会员管理" lay-direction="2">
                <i class="layui-icon layui-icon-username"></i>
                <cite>会员管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/user/user/index.html">会员列表</a></dd>
                <dd><a lay-href="/user/consum/category_sales.html">会员等级</a></dd>
                <dd><a lay-href="/user/consum/category_sales.html">会员积分</a></dd>
                <dd><a lay-href="/user/consum/category_sales.html">站内信</a></dd>
              </dl>
            </li>

            <li data-name="seller" class="layui-nav-item">
              <a href="javascript:;" lay-tips="分销管理" lay-direction="2">
                <i class="layui-icon layui-icon-user"></i>
                <cite>分销管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/seller/consum/o2o_month.html">分销库</a></dd>
                <dd><a lay-href="/seller/consum/o2o_month.html">分销商列表</a></dd>
                <dd><a lay-href="/seller/consum/category_sales.html">分销等级</a></dd>
              </dl>
            </li>

            <li data-name="home" class="layui-nav-item">
              <a href="javascript:;" lay-tips="主页" lay-direction="2">
                <i class="layui-icon layui-icon-website"></i>
                <cite>商户管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd data-name="console"><a lay-href="/merchant/merchant/list.html">商户列表</a></dd>
                <dd data-name="sell"><a lay-href="/merchant/shop/list.html">商户门店</a></dd>
                <dd data-name="sell"><a lay-href="javascript:;">调整单</a></dd>
              </dl>
            </li>

            <li data-name="promote" class="layui-nav-item">
              <a href="javascript:;" lay-tips="推广管理" lay-direction="2">
                <i class="layui-icon layui-icon-share"></i>
                <cite>推广管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/promote/consum/o2o_month.html">推广管理</a></dd>
              </dl>
            </li>

            <li data-name="article" class="layui-nav-item">
              <a href="javascript:;" lay-tips="文章管理" lay-direction="2">
                <i class="layui-icon layui-icon-list"></i>
                <cite>文章管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/article/special/list.html">专题页</a></dd>
                <dd><a lay-href="/article/article/list.html">文章列表</a></dd>
                <dd><a lay-href="/article/categorys/list.html">分类管理</a></dd>
              </dl>
            </li>

            <li data-name="article" class="layui-nav-item">
              <a href="javascript:;" lay-tips="文章管理" lay-direction="2">
                <i class="layui-icon layui-icon-carousel"></i>
                <cite>广告管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/picshow/position/list.html">广告位</a></dd>
                <dd><a lay-href="/picshow/index/list.html">广告列表</a></dd>
                <dd><a lay-href="/picshow/module/list.html">内容模块</a></dd>
                <dd><a lay-href="/picshow/qrcode/list.html">生成二唯码</a></dd>
              </dl>
            </li>

            <li data-name="system" class="layui-nav-item">
              <a href="javascript:;" lay-tips="系统设置" lay-direction="2">
                <i class="layui-icon layui-icon-set"></i>
                <cite>系统设置</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/system/index/index.html">系统设置</a></dd>
                <dd><a lay-href="/system/delivery/list.html">运费模板</a></dd>
                <dd><a lay-href="/system/express/list.html">物流公司代码</a></dd>
                <dd><a lay-href="/system/logs/list.html">操作记录</a></dd>
              </dl>
            </li>

            <li data-name="apps" class="layui-nav-item">
              <a href="javascript:;" lay-tips="应用管理" lay-direction="2">
                <i class="layui-icon layui-icon-app"></i>
                <cite>应用管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/apps/app_auth/list.html">25BOY应用</a></dd>
                <dd><a lay-href="/apps/app_third/list.html">第三方应用</a></dd>
              </dl>
            </li>

            <li data-name="document" class="layui-nav-item">
              <a href="javascript:;" lay-tips="合同文件" lay-direction="2">
                <i class="layui-icon layui-icon-file"></i>
                <cite>合同文件</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="/document/shop/doc_list.html">店铺合同文件</a></dd>
                <dd><a lay-href="/document/seller/doc_list.html">分销合同文件</a></dd>
                <dd><a lay-href="/document/index/index.html">其他合同文件</a></dd>
              </dl>
            </li>

            <li data-name="home" class="layui-nav-item">
              <a href="javascript:;" lay-tips="BI分析" lay-direction="2">
                <i class="layui-icon layui-icon-fonts-strong"></i>
                <cite>BI分析</cite>
              </a>
              <dl class="layui-nav-child">
                <!-- <dd><a lay-href="/analysis/sell/summary.html">会员分析</a></dd> -->
                <dd><a lay-href="/bi/consum/o2o_month.html">销售业绩</a></dd>
                <dd><a lay-href="/bi/consum/category_sales.html">分类销售</a></dd>
                <dd data-name="form">
                  <a href="javascript:;">会员消费</a>
                  <dl class="layui-nav-child">
                    <dd><a lay-href="/bi/user/o2o_consum_order">实体店消费</a></dd>
                  </dl>
                </dd>
              </dl>
            </li>

            <li data-name="power" class="layui-nav-item">
              <a href="javascript:;" lay-tips="权限管理" lay-direction="2">
                <i class="layui-icon layui-icon-set"></i>
                <cite>权限管理</cite>
              </a>
              <dl class="layui-nav-child">
              	<dd><a lay-href="/power/admin/page_list.html">管理员列表</a></dd>
                <dd><a lay-href="/power/power_role/page_list.html">角色列表</a></dd>
                <dd><a lay-href="/power/power_group/page_list.html">权限组列表</a></dd>
              </dl>
            </li>

          </ul>
        </div>
      </div>

      <!-- 页面标签 -->
      <div class="layadmin-pagetabs" id="LAY_app_tabs">
        <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
        <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
        <div class="layui-icon layadmin-tabs-control layui-icon-down">
          <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
            <li class="layui-nav-item" lay-unselect>
              <a href="javascript:;"></a>
              <dl class="layui-nav-child layui-anim-fadein">
                <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
              </dl>
            </li>
          </ul>
        </div>
        <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
          <ul class="layui-tab-title" id="LAY_app_tabsheader">
            <li lay-id="home/console.html" lay-attr="home/console.html" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
          </ul>
        </div>
      </div>


      <!-- 主体内容 -->
      <div class="layui-body" id="LAY_app_body">
        <div class="layadmin-tabsbody-item layui-show p-1">
          <iframe src="/analysis/sell/summary.html" frameborder="0" class="layadmin-iframe"></iframe>
        </div>
      </div>

      <!-- 辅助元素，一般用于移动设备下遮罩 -->
      <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
  </div>

  <script src="/static/layui/layui.js"></script>
  <script>

  layui.config({
    base: '/static/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use('index');

  // 返回上一页
  $('#return').on('click', function(){
    history.go(-1);
  })


  $('#logout').on('click', function(){

    console.info('logout');

    $.get('/index/auth/logout', function(res){
      if (res.code == 0) {
        if (res.data && (undefined != res.data['path'])) {
          location.href = '/index/index/' + res.data['path'];
        } else {
          location.href = '/index/index/index';
        }
      }
    })

  })

  // 转跳到登录页面
  function goLogin(){
    let loginPage = localStorage.getItem('loginPage');
    console.info(loginPage);
  }


  </script>
</body>
</html>


