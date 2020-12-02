<?php
namespace app\admin\controller;

use think\Session;

class Index extends Base
{
    public function index()
    {
        //查询登录用户权限
        $id=session('id');
        //根据用户id查询对应权限
        $data=collection(model('Admin')->field('distinct power.*')->join('a_r','admin.id=a_r.admin_id')->join('r_p','a_r.role_id=r_p.role_id')->join('power','r_p.power_id=power.id')->where('admin_id',$id)->select())->toArray();
        //拼接权限地址
        foreach($data as $v) {
            $url[]=$v['power_controller'].'/'.$v['power_action'];
        }
        Session::set('url',$url);
        return view('index',['data'=>$data]);
    }
}
