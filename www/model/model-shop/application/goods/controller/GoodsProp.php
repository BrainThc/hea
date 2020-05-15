<?php
/**
 * 商品属性
 */
namespace app\goods\controller;

class GoodsProp extends Base
{
    //获取所有标签列表
    public function getPropAll(){
        if( !$checkData = $this->validate->getPropAll(input('get.')) ){
            return errorJson(0204301, $this->validate->getError());
        }
        $list = $this->model->getPropAll($checkData['page'],$checkData['limit'],true);
        return successJson($list['list'],'',$list['count']);
    }

    public function getFilterList(){
        $filterList = $this->model->getFilterList();
        return successJson($filterList,'success');
    }

    public function deleted(){
        if( !$checkData = $this->validate->getPropInfo(input('post.')) ){
            return errorJson(020401, $this->validate->getError());
        }
        if( !$this->model->deleted($checkData['id']) ){
            return errorJson($this->model->getCode(), $this->model->getError());
        }
        return successJson(true,'删除成功');
    }

}
