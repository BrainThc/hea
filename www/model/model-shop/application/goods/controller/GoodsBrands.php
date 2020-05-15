<?php
/**
 * 商品品牌
 */
namespace app\goods\controller;

class GoodsBrands extends Base
{

    public function indexBefore(){
        $where['is_deleted'] = 0;
        $this->model->where($where)->order(['sort'=>'desc','brand_letter'=>'asc']);
    }

    public function allBefore(){
        $where['is_deleted'] = 0;
        $this->model->where($where)->order(['sort'=>'desc','brand_letter'=>'asc']);
    }

    public function getFilterList(){
        $filterList = $this->model->getFilterList();
        return successJson($filterList,'success');
    }

    public function deleted(){
        if( !$checkData = $this->validate->deleted(input('post.')) ){
            return errorJson(020301, $this->validate->getError());
        }
        if( !$this->model->deleted($checkData['id']) ){
            return errorJson($this->model->getCode(), $this->model->getError());
        }
        return successJson(true,'删除成功');
    }
}
