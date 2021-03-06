<?php

/**
 * 服务接口对象
 */

namespace app\common\controller;

use think\Controller;

class Service extends Controller{

    // curl请求头的key
    const HEADER_KEY = '25boy-admin-livermore';

    protected $host = [
        // 'user' => 'http://user.25boy.cn/', // 线上用户模块主机地址
        // 'user' => 'http://user.25boy.com/', // 本地用户模块主机地址
    ];

    // 使用主机地址
    protected $useHost = '';

    protected $fiexdParams = [];

    // curl错误码
    protected $errorCode;

    // 初始化方法
    public function _initialize(){
        parent::_initialize();
            
        if (IS_WIN) {
            $this->host['center'] = 'http://control-center.25boy.com/';
            $this->host['shop'] = 'http://control-shop.25boy.com/';
            // $this->host['user'] = 'http://user.25boy.cn/';
            
            // 中心数据层接口地址 
            $this->host['center_data'] = 'http://data-center.25boy.com/';
            $this->host['shop_data'] = 'http://data-shop.25boy.com/';
        } else {
            $this->host['center'] = 'http://control-center.25boy.cn/'; // 线上用户模块主机地址
        }
        
        // 添加session固定参数
        if (session('shop')) {
            // 添加店铺id
            $this->fiexdParams['shop_id'] = session('shop')['id'];
        }
        
        // 如果是员工登录
        if (session('staff')) {
            // 添加店铺id
            $this->fiexdParams['shop_id'] = session('staff')['shop_id'];
            // 操作员工id
            $this->fiexdParams['ctrl_staff_id'] = session('staff')['id'];
        }
        
        if(session('admin')){
            //管理员id
            $this->fiexdParams['admin_id'] = session('admin.id');
            //管理员角色id
            $this->fiexdParams['admin_role_id'] = session('admin.role')['id'];
            //管理员身份,1超级管理员（不能创建不能修改不能删除，不受权限控制），2后台，3商户持有者，4商户员工
            $this->fiexdParams['admin_account_type'] = session('admin.account_type');
        }

    }

    /**
     * [addFiexdParams 添加固定参数]
     * @param array $params [必须是关联数组]
     */
    public function addFiexdParams($params = []){
        // 添加固定参数
        foreach ($params as $k => $v) {
            $this->fiexdParams[$k] = $v;
        }
    }


    /**
     * [setHots 设置使用主机]
     * @param [type] $host_label [description]
     */
    public function setHots($host_label) {
        return $this->setHost($host_label);
    }

    /**
     * [setHost 设置使用主机]
     * @param [type] $host_label [description]
     */
    public function setHost($host_label) {
        if (!in_array($host_label, array_keys($this->host))) {
            pe('主机地址不存在');
        }
        $this->useHost = $this->host[$host_label];
        return $this;
    }


	/**
	 * [get GET方式的请求方法]
	 * @param  [type] $url    [API地址]
	 * @param  [type] $params [提交参数]
	 * @return [type]         [description]
	 */
	public function get($url, $params = []){
		// 组合参数
		foreach ($params as $k => $v) {
            if ($k == 0) {
                $url .= '?';
            }
			$url .= '&'.$k.'='.$v;
		}

        // 如果url没有？
        if ($this->fiexdParams && strpos($url, '?') == FALSE) {
            $url .= '?';
        }

        // 添加固定参数
        foreach ($this->fiexdParams as $k => $v) {
            $url .= '&'.$k.'='.$v;
        }

		// 组合完整url
		$url = $this->useHost . trim($url,'/');

        // pe($url);

		return $this->curl_get($url);
	}

	/**
	 * [post POST方式的请求方法]
	 * @param  [type] $url    [description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function post($url, $params){
		// 组合完整url
		$url = $this->useHost . trim($url,'/');

        // 添加固定参数
        foreach ($this->fiexdParams as $k => $v) {
            $params[$k] =$v;
        }
		return $this->curl_post($url, $params);
	}

	/**
     * 自定义curl发送post数据方法
     * @param string 上传的地址
     * @param [array] [$data] [发送的数据]
     * @return [type] [description]
     */
    protected function curl_post($url,$data){
        //开启curl
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //绕过权限验证
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_TIMEOUT,5);  //定义超时5秒钟  
         // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));    //所需传的数组用http_bulid_query()函数处理一下，就ok了
        
        $header = [
            'key:'.self::HEADER_KEY
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        //执行并获取url地址的内容
        $output = curl_exec($ch);
        $errorCode = curl_errno($ch);

        $this->errorCode = $errorCode;

        //释放curl句柄
        curl_close($ch);
        if(0 !== $errorCode) {
            // 记录curl错误日志
            $info = [
                'error' => 'curl请求错误：'.$errorCode,
                'url'   => $url,
                'data'  => $data
            ];
            \think\Log::write($info, 'error');
            return false;
        }


        return $output; 
    }

    /**
     * 自定义curl发送get数据方法
     * @param string 上传的地址
     * @return [type] [description]
     */
    protected function curl_get($url){
        //开启curl
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        //捕获内容，但不输出
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //绕过权限验证
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        //设置get请求
		//curl_setopt($ch,CURLOPT_GET, 1);

        $header = [
            'key:'.self::HEADER_KEY
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        //执行并获取url地址的内容
        $output = curl_exec($ch);
        $errorCode = curl_errno($ch);

        $this->errorCode = $errorCode;

        //释放curl句柄
        curl_close($ch);
        if(0 !== $errorCode) {
            return false;
        }

        return $output; 
    }

    /**
     * [getErrorCode 获取错误码]
     * @return [type] [description]
     */
    public function getErrorCode(){
        return $this->errorCode;
    }

}