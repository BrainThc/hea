<?php
/**
 * 文章分类
 */
namespace app\article\controller;
use think\Db;


class Categorys extends Base
{
	//获取所有分类
	public function getCateAll()
	{  
        $checkData = $this->validate->getCateAll(input());
		if( $checkData === false ){
            return errorJson(200201, $this->validate->getError());
        }
        $list = $this->model->getCateAll($checkData);
        return successJson($list,$msg="success");
	}
    //分类名是否重复
	public function checkRepeatCateName()
    {
		$param = input("post.");
		if( empty($param['name']) ){
            return errorJson(200201, '参数错误');
        }
        if( isset($param['id']) ){
            $where['id'] = ['<>',$param['id']];
        }
        $where['name'] = ['=',$param['name']];
        return successJson(!$this->model->checkRepeatCateName($where));
	}


    protected function deleteBefore(){
        $ids = input('param.ids');
        // 输出错误提示
        if (empty($ids)) {
            $this->code = 10001;
            $this->error = '缺失参数ids';
            $this->isExit = true;
        } else {
            // 设置模型数据
            $this->model->data(['ids' => $ids]);
        }
    }

}

?>