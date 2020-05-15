<?php

namespace app\share\model;

use app\common\controller\Service;

class Share extends Base
{

    const SHARE_VERIFY_WAIT = 0;
    const SHARE_VERIFY_PAST = 1;
    const SHARE_VERIFY_LOST = 2;

    public static $map_verify = array(
        self::SHARE_VERIFY_WAIT => array(
            'desc' => '待审核'
        ),
        self::SHARE_VERIFY_PAST => array(
            'desc' => '通过'
        ),
        self::SHARE_VERIFY_LOST => array(
            'desc' => '不通过'
        ),
    );

    public function indexAfter(){
        if( !empty($this->data) ){
            foreach($this->data as $k => $v ){
                $v['verify_desc'] = self::$map_verify[$v['verify']]['desc'];
                $this->data[$k] = $v;
            }
        }
    }

    public function addBefore(){
        if( isset($this->data['images_list']) ){
            $this->data['images_list'] = empty($this->data['images_list']) ? '' : json_encode($this->data['images_list']);
        }
        $this->data['default_content'] = json_encode($this->data);
        $this->data['create_time'] = date('Y-m-d H:i:s');
        $this->data['update_time'] = $this->data['create_time'];
        return true;
    }

    public function oneAfter(){
        if( !empty($this->data) ){
            $server = new Service();
            $this->data['user_info'] = $server->setHost('center_data')->post('user/user/one',['id'=>$this->data['user_id']]);
            $this->data['images_list'] = empty($this->data['images_list']) ? [] : json_decode($this->data['images_list'],true);
        }
    }

    public function editBefore(){
        $this->data['update_time'] = date('Y-m-d H:i:s');
        //检查是否存在
        $info = $this->where("id = {$this->data['id']}")->find();
        if(empty($info)){
            $this->code = 010704;
            $this->isExit = true;
            $this->error = "信息获取失败";
            return false;
        }
        if( isset($this->data['images_list']) ){
            $this->data['images_list'] = empty($this->data['images_list']) ? '' : json_encode($this->data['images_list']);
        }
        return true;
    }

}
