<?php
/**
 * 商品销售参数规格
 */
namespace app\goods\controller;

class AttributeCate extends Base
{

    public function getAttrCateList(){
        if( !$checkData = $this->validate->getAttrCateList(input('get.')) ){
            return errorJson(020501, $this->validate->getError());
        }
        $list = $this->model->getAttrCateAll($checkData['page'],$checkData['limit'],true);
        return successJson($list['list'],'',$list['count']);
    }

    public function getFilterList(){
        $filterList = $this->model->getFilterList();
        return successJson($filterList,'success');
    }

    public function deleted(){
        if (!$checkData = $this->validate->deleted(input('post.'))) {
            // 设置错误信息和错误码
            return errorJson(020501, $this->validate->getError());
        }
        if( !$this->model->deleted($checkData['id']) ){
            return errorJson($this->model->getCode(), $this->model->getError());
        }
        return successJson(true,'删除成功');
    }

}
