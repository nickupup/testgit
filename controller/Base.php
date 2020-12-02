<?php

namespace app\admin\controller;

use think\Session;
use think\Controller;
use think\Request;

class Base extends Controller
{
    protected $url=['admin/loginform','admin/login'];
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $id=Session::get('id');
        //获取当前访问的控制器与方法
        $nowurl=strtolower($this->request->controller().'/'.$this->request->action());
        if (!in_array($nowurl,$this->url)){
            //判断是否登录
            if (empty($id)){
                $this->error('请先登录','admin/loginform');
            }
            //获取当前用户可访问权限地址
            $url=Session::get('url');
            //判断有没有权限访问当前地址
            if (!in_array($nowurl,$url)&&$nowurl!='index/index'&&$nowurl!='admin/logout'){
                $this->error('您没有此权限','index/index');
            }
        };
    }
}
