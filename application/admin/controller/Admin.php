<?php

namespace app\admin\controller;

use think\Request;
use app\admin\controller\Base;
use app\common\model\Admin as adminModel;
use app\common\model\AdminRole;

class Admin extends Base
{
    public function _initialize(){
        //
        $roles=AdminRole::all();
        $roleArr=[];
        foreach ($roles as $k=>$v){
            $roleArr[$v->id]=$v->name;
        }
        $this->assign('roles',$roleArr);
        
        $this->assign('sexArr',[1=>'男',2=>'女']);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list=adminModel::paginate(10);
        //diylog($list,'list');
        //trace($list,'debug');
        //Log::write(var_export($list->toArray(),true),'debug');
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
        
        
        $this->assign('data',[]);
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
        $post=$request->post();
        $adminM=new adminModel();
        $adminM->data($post);
        $affected=$adminM->allowField(true)->save();
        if($affected){
            return [
                'code'=>0
            ];
        }else{
            return [
                'code'=>-1,
                'msg'=>'sql错误:'.$adminM->getError()
            ];
        }
        
        
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
        $data=adminModel::get($id);
        //diylog($data,'data');
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
        $adminM=new adminModel;
        $id=$adminM->allowField(true)->save($post,['id'=>$id]);
        if (!$id){
            //diylog($adminModel);
            return [
                'code'=>-1,
                'msg'=>'sql错误：'.$adminM->getError()];
        }
        return [
            'code'=>0,
            'data'=>$adminM->getData()
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
        adminModel::destroy($id);
        return ['code'=>0];
    }
}
