<?php

namespace app\depot\model;

class Shop
{

	/**
	 * [getOne 获取店铺信息]
	 * @param  [type] $shop_id [店铺id]
	 * @return [type]          [description]
	 */
	public function getOne($shop_id){
		// pe($shop_id);
		$service = new \app\common\Controller\Service();
		$shop = $service->setHost('center_data')->get('/merchant/shop/one?id='.$shop_id);
		return $shop;
	}


}