<?php

namespace app\goods\model;

class GoodsProp extends Base
{
    protected $name = 'goods_prop';

    //显示类型
    const SHOW_TYPE_TXT = 0;  //文字
    const SHOW_TYPE_ALL = 1;   //图文
    const SHOW_TYPE_IMG = 2;   //图片
    const SHOW_TYPE_COLOR = 3;   //颜色

    //阅读类型描述映射
    public static $map_show_type = array(
        self::SHOW_TYPE_TXT => array(
            'desc' => '文字'
        ),
        self::SHOW_TYPE_ALL => array(
            'desc' => '图文'
        ),
        self::SHOW_TYPE_IMG => array(
            'desc' => '图片'
        ),
        self::SHOW_TYPE_COLOR => array(
            'desc' => '颜色'
        )
    );

    //必选参数
    const OFF_CHANGED = 0;  //否
    const ON_CHANGED = 1;   //是

    public static $map_changed = array(
        self::OFF_CHANGED => array(
            'desc' => '否'
        ),
        self::ON_CHANGED => array(
            'desc' => '是'
        )
    );

    public function getPropAll($page=1,$limit=10,$getCount=false){
        $pageSize = ( $page - 1 ) * $limit.','.$limit;
        if($limit=0){
            $pageSize = '';
        }
        $where['is_deleted'] = 0;
        $data['list'] = $this->where($where)->limit($pageSize)->order('sort','desc')->select();
        if( !empty($data['list']) ){
            foreach( $data['list'] as $key => $val ){
                $val['show_type_desc'] = self::$map_show_type[$val['show_type']]['desc'];
                $val['is_changed_desc'] = self::$map_changed[$val['is_changed']]['desc'];
                $data['list'][$key] = $val;
            }
        }
        if($getCount){
            $num = $this->where($where)->value('count(id)');
            if(empty($num)){
                $num = 0;
            }
            $data['count'] = $num;
        }
        return $data;
    }

    public function getFilterList(){
        $goodsModel = new \app\goods\model\Goods();
        $goodsItemModel = new \app\goods\model\GoodsItem();
        $goodsPropValModel = new \app\goods\model\GoodsPropVal();
        $goodsItemPropValModel = new \app\goods\model\GoodsItemPropVal();
        $prop_all = $goodsItemPropValModel->alias('ipv')
            ->field('p.id as p_id,p.prop_name,p.show_type,pv.pv_name,pv.id as pv_id,pv.pv_type_val as type_val')
            ->join($goodsPropValModel->getTable().' pv',' pv.id = ipv.goods_prop_val_id')
            ->join($this->getTable().' p','p.id = pv.goods_prop_id')
            ->join($goodsItemModel->getTable().' i','i.id = ipv.goods_item_id')
            ->join($goodsModel->getTable().' g','g.id = i.goods_id')
            ->where([
                'p.is_deleted'=>0,
                'pv.is_deleted'=>0,
                'i.sales_status'=>1,
                'i.is_invalid'=>0,
                'i.is_deleted'=>0,
                'g.is_deleted'=>0,
                'g.verify'=>1,
                'g.sales_status'=>1,
            ])
            ->group('ipv.goods_prop_val_id')
            ->order([
                'p.sort'=>'desc',
                'pv.sort'=>'desc'
            ])
            ->select();
        $prop_list = [];
        if( !empty($prop_all) ){
            foreach( $prop_all as $prop ){
                if( !isset($prop_list[$prop['p_id']]) ){
                    $prop_list[$prop['p_id']] = [
                        'p_name' => $prop['prop_name'],
                        'show_type' => $prop['show_type'],
                        'pv_list' => [],
                    ];
                }
                $prop_list[$prop['p_id']]['pv_list'][] = [
                    'pv_id' => $prop['pv_id'],
                    'pv_name' => $prop['pv_name'],
                    'show_val' => $prop['type_val']
                ];
            }
        }
        return $prop_list;
    }

    public function oneAfter($ids){
        $this->data['show_type_desc'] = self::$map_show_type[$this->data['show_type']]['desc'];
        $this->data['is_changed_desc'] = self::$map_changed[$this->data['is_changed']]['desc'];
    }

    public function getGoodsPropList($ids){
        $info = $this->where('id',$ids)->find();
        if(empty($info)){
            $this->code = 020404;
            $this->error = "属性类型不存在";
            return false;
        }
        $info['show_type_desc'] = self::$map_show_type[$info['show_type']]['desc'];
        $info['is_changed_desc'] = self::$map_changed[$info['is_changed']]['desc'];
        return $info;
    }

    public function addBefore(){
        $this->data['create_time'] = date('Y-m-d H:i:s');
        $this->data['update_time'] = $this->data['create_time'];
        if(!$this->checkRepeatPropName()){
            return false;
        }
        return true;
    }

    public function editBefore(){
        $this->data['update_time'] = date('Y-m-d H:i:s');
        $where['id'] = ['<>',$this->data['id']];
        if(!$this->checkRepeatPropName($where)){
            return false;
        }
        return true;
    }

    public function checkRepeatPropName($where=[]){
        if(!empty($this->data['prop_name'])){//前置方法使用
            $this->where('prop_name',$this->data['prop_name']);
        }
        if( !empty($where) ){
            foreach( $where as $key => $val ) {
                $this->where($key,$val[0],$val[1]);
            }
        }
        $info = $this->find();
        if($info){
            $this->code = 020402;
            $this->isExit = true;
            $this->error = "属性名已存在！";
            return false;
        }
        return true;
    }

    public function deleted($ids){
        //检查是否存在
        $info = $this->getPropInfo($ids);
        if( empty($info) ){
            $this->code = 020402;
            $this->error = "属性类型不存在！";
            return false;
        }
        //检查是否存在商品绑定
        $goodsPropValModel = new \app\goods\model\GoodsPropVal();
        $returnList = $goodsPropValModel->getPropValGoods($ids,$goodsPropValModel::CHECK_TYPE_P);
        if( !empty($returnList) ){
            $this->code = 020405;
            $this->error = "存在商品绑定不允许删除！";
            return false;
        }
        //执行删除
        $updateData['is_deleted'] = 1;
        $updateData['update_time'] = date('Y-m-d H:i:s');
        if( !$this->where('id',$ids)->update($updateData) ){
            $this->code = 020410;
            $this->error = '网络错误，删除失败';
            return false;
        }
        return true;
    }
}
