<template>
	<view class="container">
		<topbar />
		<!-- 轮播图 -->
		<swiper class="home-swipe" v-if="slides && slides.length" autoPlay>
      <swiper-item v-for="(slide, index) in slides" :key="index">
        <div class="home-swipe-item">
          <a :href="slide.url">
            <img :src="slide.srcurl + '!w800'" lazy-load class="home-swipe-image" >
          </a>
        </div>
      </swiper-item>
    </swiper>
		<!-- 快捷菜单 -->
		<van-row class="home-fastmenu">
      <van-col span="6">
        <a href="//m.25boy.cn/?m=category&a=singlePage">
          <img src="https://img.25miao.com/695/51b548bd73c9c82e3e45e0bd730c88e4.png!w200" mode="widthFix">
          <span>商品分类</span>
        </a>
      </van-col>
      <van-col span="6">
        <a href="//m.25boy.cn/?m=o2o&a=store">
          <img src="https://img.25miao.com/695/6b702204444c8fb99240a702948386d0.png!w200" mode="widthFix">
          <span>线下同价</span>
        </a>
      </van-col>
      <van-col span="6">
        <a href="//m.25boy.cn/?m=share">
          <img src="https://img.25miao.com/695/b19855a08a45c24cbf3c90e2abdf7ebc.png!w200" mode="widthFix">
          <span>达人晒图</span>
        </a>
      </van-col>
      <van-col span="6">
        <a href="//m.25boy.cn/h5/subscribe.html">
          <img src="https://img.25miao.com/695/68fe26d9ac0cab63242afdbf4ad21712.png!w200" mode="widthFix">
          <span>微信包邮</span>
        </a>
      </van-col>
    </van-row>
		<!-- 放假通知 -->
    <div class="background-white" v-if="holiday && holiday.length">
      <div class="home-panel" v-for="(item, index) in holiday" :key="index">
        <div class="home-panel-title">
          <span class="title-line van-ellipsis">{{item.holiday_title}}</span>
        </div>
        <div class="home-panel-body">
          <img class="image" :src="item.holiday_pic+'!w800'" mode="widthFix">
        </div>
      </div>
    </div>
		<!-- 活动通栏图 -->
    <div class="background-white mt--2" v-if="activityBanner && activityBanner.length">
      <div class="home-panel" v-for="(item, index) in activityBanner" :key="index">
        <div class="home-panel-title">
          <span class="title-line van-ellipsis">{{item.adname}}</span>
        </div>
        <div class="home-panel-body">
          <a :href="item.url">
            <img class="image" :src="item.srcurl+'!w800'" mode="widthFix">
          </a>
        </div>
      </div>
    </div>
		<!-- 分类 -->
    <div class="background-white mt--2">
      <div class="home-panel">
        <div class="home-panel-title">
          <span class="title-line">热门品类</span>
          <a class="title-more iconfont icon-more" href="//m.25boy.cn/?m=category&a=singlePage"></a>
        </div>
        <div class="home-panel-body" v-if="categorys && categorys.length">
          <div class="category">
            <div class="category-item" v-for="(item, index) in categorys" :key="index">
              <a :href="item.url">
                <img :src="item.srcurl+'!w200'" class="category-image" mode="widthFix">
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 精选 -->
    <div class="background-white mt--2" v-if="boyChosen && boyChosen.length">
      <div class="home-panel">
        <div class="home-panel-title">
          <span class="title-line">25精选</span>
        </div>
        <div class="home-slideGoods" v-for="(group, index) in boyChosen" :key="index">
          <div class="image" v-if="group.banner">
            <a :href="group.banner.url">
              <img class="image" :src="group.banner.srcurl+'!w800'" mode="widthFix">
            </a>
          </div>
          <scroll-view scroll-x class="list-wrapper mt--2" tag="div" v-if="group.list">
            <ul class="item-content" :style="{width: (((group.list.length+1)*goodsItemWidth)+'rpx')}">
              <li v-for="(item, idx) in group.list" :key="idx" class="goods-item">
                <a :href="'//m.25boy.cn/?m=category&a=product&id='+item.product_id">
                  <img :src="item.product_img+'!w390'" lazy-load class="goods-image">
                </a>
                <div class="goods-text">
                  <div class="goods-brand van-ellipsis">{{item.brand_name}}</div>
                  <div class="goods-name van-ellipsis">{{item.product_name}}</div>
                  <div class="goods-price van-ellipsis">¥{{item.price}}</div>
                </div>
              </li>
              <a class="goods-item goods-item-more" v-if="group.banner" :href="group.banner.url"></a>
            </ul>
          </scroll-view>
        </div>
      </div>
    </div>
    <!-- 晒图 -->
    <div class="background-white mt--2">
      <div class="home-panel">
        <div class="home-panel-title">
          <span class="title-line">达人晒图</span>
          <a class="title-more iconfont icon-more" href="//m.25boy.cn/?m=share"></a>
        </div>
        <swiper class="share-swipe" v-if="shareList && shareList.length>0">
          <swiper-item :autoPlay="3000" :showIndicator="showIndicator" v-for="item in shareList" :key="item.share_id">
            <div class="share-swipe-item">
              <a :href="'//m.25boy.cn/?m=share&a=view&id='+item.share_id">
                <img :src="item.image+'!w800'" lazy-load class="share-swipe-image" mode="aspectFill">
              </a>
              <div class="share-swipe-text">
                <span class="share-swipe-avatar">
                  <img :src="item.userimg" class="share-swipe-avatar-image">
                  <i class="share-swipe-text-label">{{item.username}}：</i>
                </span>
                {{item.content}}
              </div>
            </div>
          </swiper-item>
        </swiper>
      </div>
    </div>
		<!-- 品牌tab -->
    <div class="background-white mt--2 pb--5 pos-r">
      <van-tabs v-model="tabActive" @change="changeTab" sticky animated>
        <van-tab v-for="(tab, index) in tabs" :title="tab.name" :key="index">
          <div class="goodsCol pl--2 pr--2 pt--1">
            <goods-item v-for="(item, idx) in tab.list" :key="idx" :item="item"></goods-item>
          </div>
        </van-tab>
      </van-tabs>
			<uni-load-more status="loading" v-if="loading=='loading'"></uni-load-more>
    </div>
		
	</view>
</template>

<script>
import UTIL from '@/utils/util'
import Topbar from '../../components/topbar'
import GoodsItem from '../../components/goods-item'
import {uniLoadMore} from '@dcloudio/uni-ui'

export default {
	components: {
    Topbar, GoodsItem, uniLoadMore
  },
	data() {
		return {
			// 左右滑动单个项目宽度
      goodsItemWidth: 280,
			// 首页数据
			homeData: {},
			// 附近店铺
			nearStoreInfo: {},
			// tabs
      tabActive: 0,
      tabs: [{
        id: 5,
        name: 'HEA',
        list: [],
        page: 0
      },
      {
        id: 4,
        name: '银鳞堂',
        list: [],
        page: 0
      },
      {
        id: 3,
        name: 'HE75 DENIM',
        list: [],
        page: 0
      }],
      // 加载中
      loading: 'more',
			pages: {}
		}
	},
	computed: {
    slides () {
      return this.homeData.bannerList
    },
    activityBanner () {
      return this.homeData.activity_banner
    },
    categorys () {
      return this.homeData.index_categorys_items
    },
    shareList () {
      return this.homeData.shareList
    },
    activityPopup () {
      return this.homeData.activityPopup
    },
    boyChosen () {
      return this.homeData.boyChosen
    },
    holiday () {
      return this.homeData.holiday
    }
  },
	methods: {
		// 获取首页数据
    async getHomeData () {
      let _this = this
      let params = {}

      uni.showLoading({
				title: '加载中'
			})

      // 获取附近店铺
      let json = await _this.$http.get('o2o/getNearStores')
      if (json.code === 0) {
        this.nearStoreInfo = json.rs
        params.business_id = json.rs.business_id
      }

      _this.$http.get('index/v3', params).then(res => {
        uni.hideLoading()
				uni.stopPullDownRefresh()
        if (res.code === 0) {
          _this.homeData = res.rs.data
        } else {
					uni.showToast({
						title: res.msg,
						icon: 'none',
						duration: 2000
					})
        }
      })
    },
		// 商品列表
    getProductList () {
      let _this = this
      let active = _this.tabActive
      let tab = _this.tabs[active]
      let list = tab.list
      let page = tab.page
			
      // 检查是否可以加载
      if (_this.loading === 'loading' || _this.loading === 'noMore') {
        return false
      } else {
        page += 1
      }
      // loading
      _this.loading = 'loading'

      // ajax获取产品列表
      const params = {
        brand_id: tab.id,
        pageNo: page
      }
      _this.$http.get('product/productList', params).then(res => {
        if (res.code === 0 && res.rs.data.length > 0) {
          tab.list = [...list, ...res.rs.data]
          tab.page = page
          _this.tabs[active] = tab
					_this.pages = res.rs.pageList
					UTIL.pages(_this.pages, function (res) {
						if (res) {
							_this.loading = 'more'
						} else {
							_this.loading = 'noMore'
						}
					})
        } else {
          _this.tabs[active].page = page
					_this.loading = 'noMore'
        }
      })
    },
    // 切换标签
    changeTab (e) {
      const index = e.mp.detail.index
      const tab = this.tabs[index]
      this.tabActive = index
      this.loading = 'more'
      if (tab.list.length === 0) {
        this.getProductList()
      }
    }
	},
	onLoad() {
		this.getHomeData()
		this.getProductList()
	},
	onReachBottom () {
    this.getProductList()
  },
	onPullDownRefresh () {
		this.getHomeData()
	}
}
</script>

<style lang="less">
body,page{background-color:#f2f2f2;}
.container{padding-top:84upx !important;}
.home{
  &-swipe {
    width:750upx;height:422upx;z-index:1;
    &-image{width:100%;height:422upx;display:block;}
  }
  &-fastmenu{
    background-color:#fff;text-align:center;width:700upx;margin:auto;box-shadow:0px 1upx 12upx rgba(0,0,0,0.2);padding:20upx 10upx;position:relative;top:-20upx;box-sizing:border-box;border-radius:3px;display:block;z-index:2;
    .van-col{text-align:center;}
    img{display:block;width:60upx;margin:auto;}
    span{color:#666;font-size:26upx;margin-top:10upx;display:block;}
  }
  &-activity{
    margin-top:20upx;
  }
  &-panel{
    width:100%;
    &-title{font-size:32upx;color:#5b5b5b;font-family:"Microsoft YaHei";padding:30upx 20upx 20upx;display:flex;justify-content:space-between;align-items:center;width:100%;box-sizing:border-box;}
    .title-line{border-left:4upx solid #333;color:#333;padding-left:15upx;}
  }
}
.list-wrapper{
  width:750upx;overflow-y:hidden;padding-bottom:50upx;
  .item-content{display:flex;width:750*4upx;padding-right:20upx;}
}
.goods{
  &-list{display:flex;}
  &-item{margin-left:20upx;width:260upx;overflow:hidden;}
  &-image{display:block;width:260upx;height:260upx;}
  &-item:hover{opacity:0.7;}
  &-item-more{background-image:url(https://img.25miao.com/695/86dbe99ef9d2bf3b43227a33fb12fe7c.png);background-repeat:no-repeat;background-position:center;background-size:cover;}
  &-text{padding:10upx;line-height:40upx;}
  &-name{font-size:26upx;color:#666;}
  &-brand{font-size:26upx;color:#000;}
  &-price{font-size:30upx;}
  &-recharge{font-size:12upx;color:red;}
}
.category{
  display:flex;flex-wrap:wrap;
  &-item{padding:10upx;width:187upx;box-sizing:border-box;border-bottom:1px solid #eee;border-left:1px solid #eee;}
  &-item:nth-child(-n+4){border-top:1px solid #eee;}
  &-item:nth-child(1),&-item:nth-child(5),&-item:nth-child(9){border-left:none;}
  &-image{width:100%;display:block;}
}
.share{
  position:relative;margin-top:30upx;
  &-swipe{
    height:400upx;width:100%;font-size:26upx;
    .wh_indicator{display:none;}
    &-item{height:400upx;overflow:hidden;}
    &-image{width:100%;height:400upx;}
    &-text{color:#000;padding:10upx 20upx;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;line-height:50upx;clear:both;position:absolute;bottom:15upx;width:100%;background:rgba(255,255,255,0.5);}
    &-text-label{font-weight:bold;font-style:normal;float:left;}
    &-avatar{clear:both;overflow:hidden;display:block;float:left;}
    &-avatar-image{width:40upx;height:40upx;border-radius:50%;display:inline-block;float:left;border:1px solid #ddd;margin-right:6upx;}
  }
}
</style>
