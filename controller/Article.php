<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Article extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收搜索田间
        $article_title=input('article_title');
        //定义搜索判断条件
        $where=[];
        if (!empty(input('article_title'))){
            $where['article_title']=['like',"%$article_title%"];
        }
        //查询文章所有数据
        $data=model('Article')->where($where)->paginate(5,false,[
            'query'=>['article_title'=>$article_title]
        ]);
        return view('show',['data'=>$data]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view('add');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收参数
        $param=$request->param();
        //验证参数
        $validate=$this->validate($param,[
            'article_title|文章标题'=>'require',
            'article_intro|文章描述'=>'require',
            'article_content|文章内容'=>'require'
        ]);
        if ($validate!==true){
            $this->error($validate);
        }
        //图片上传
        $file=$request->file('article_image');
        //图片不为空则则上传
        if (!empty($file)){
            $info=$file->move('./static/img');
            //图片上传地址
            $param['article_image']='/static/img/'.$info->getSaveName();
        }
        //入库
        $res=model('Article')::create($param,true);
        $this->redirect('index');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //验证参数
        if (!is_numeric($id)){
            $this->error('参数格式不正确');
        }
        //查询一条数据
        $data=model('Article')->find($id);
        return view('upform',['data'=>$data]);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //接收参数
        $param=$request->param();
        //验证参数
        $validate=$this->validate($param,[
            'article_title|文章标题'=>'require',
            'article_intro|文章描述'=>'require',
            'article_content|文章内容'=>'require',
            'id|文章ID'=>'require|number'
        ]);
        if ($validate!==true){
            $this->error($validate);
        }
        //图片上传
        $file=$request->file('article_image');
        //图片不为空则则上传
        if (!empty($file)){
            $info=$file->move('./static/img');
            //图片上传地址
            $param['article_image']='/static/img/'.$info->getSaveName();
        }
        else{
            unset($param['article_image']);
        }
        $res=model('Article')::update($param,['id'=>$id],true);
        $this->redirect('index');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //验证参数
        if (!is_numeric($id)){
            $this->error('参数格式不正确');
        }
        //软删除
        $res=model('Article')::destroy($id);
        $this->redirect('index');
    }
}
