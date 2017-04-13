<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Thumb as tModel;

class Thumb extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //$this->assign('callback',$callback);
        $list=tModel::where('1=1')->order('id desc')->paginate(6);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
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
        //
        $data=tModel::get($id);
        $this->assign('data',$data);
        $this->assign('action',url('update?id='.$id));
        return $this->fetch('save');
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
        //
        $post=$request->post();
        $db=new tModel();
        $id=$db->allowField(true)->save($post,['id'=>$id]);
        if (!$id){
            //diylog($adminModel);
            return [
                'code'=>-1,
                'msg'=>'sql错误：'.$db->getError()];
        }
        return [
            'code'=>0,
            'data'=>$db->getData()
        ];
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
