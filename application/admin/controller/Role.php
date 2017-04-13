<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\AdminRole;
use think\Model;

class Role extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $list=AdminRole::all();
        $this->assign('list',$list);
        $count=AdminRole::count();
        $this->assign('count',$count);
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
        $this->assign('data','');
        $this->assign('action',url('save'));
        return $this->fetch('save');
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
        $data=$request->post();
        $adminRoleModel=new AdminRole();
        $adminRoleModel->data($data);
        $adminRoleModel->allowField(true)->save();
        $id=$adminRoleModel->id;
        if (!$id){
            return [
                'code'=>-1,
                'msg'=>$adminRoleModel->getError()];
        }
        return [
            'code'=>0,
            'data'=>$id
        ];
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
        $data=AdminRole::get($id);
        $this->assign('action',url('update?id='.$id));
        $this->assign('data',$data);
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
        $data=$request->post();
        $roleModel=new AdminRole();
        //diylog($data,'post');
        //diylog($id,'id');
        $id=$roleModel->allowField(true)->save($data,['id'=>$id]);
        if (!$id){
            return [
                'code'=>-1,
                'msg'=>$roleModel->getError()];
        }
        return [
            'code'=>0,
            'data'=>$id
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
        AdminRole::destroy($id);
        return ['code'=>0];
    }
}
