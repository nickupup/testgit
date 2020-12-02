<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;

class Admin extends Base
{
    /**
     * 登录视图
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function loginForm(){
        return view('login');
    }

    /**
     * 登录
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login(){
        //接收数据
        $param=input();
        $validate=$this->validate($param,[
            'admin_name|用户名'=>'require',
            'admin_pwd|密码'=>'require'
        ]);
        if ($validate!==true){
            $this->error($validate);
        }
        //数据库中查找数据
        $data=model('Admin')->where('admin_name',$param['admin_name'])->find();
        if ($data){
            if ($param['admin_pwd']==$data['admin_pwd']){
                Session::set('id',$data['id']);
                $this-> redirect('Index/index');
            }
            else{
                $this->error('密码错误');
            }
        }
        else{
            $this->error('用户不存在');
        }
    }
    public function logout(){
        Session::set('id','');
        $this->redirect('loginform');
    }
}
