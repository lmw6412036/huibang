<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Member as mModel;

class Member extends Controller
{
    public function _initialize(){
        $this->assign('pos',get_coclasses_fid(68));
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function select(Request $request)
    {
        //
        //$this->assign('list',[]);
        $type=$request->param('type');
        $this->assign('type',$type);
        $list=mModel::where([
            'type'=>$type
        ])->order('sort desc')->paginate(10);
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        //
        //$this->assign('list',[]);
        $type=$request->param('type');
        $this->assign('type',$type);
        $list=mModel::where([
            'type'=>$type
        ])->order('sort desc')->paginate(10);
        $this->assign('list',$list);
        return $this->fetch($type);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create($type)
    {
        //
        $this->assign('action',url('save'));
        $this->assign('type',$type);
        $this->assign('data',[]);
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
        if(isset($post['pos'])){
            $post['pos']=implode(',',$post['pos']);
        }
        $adminM=new mModel();
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
        $data=mModel::get($id);
        //$data->banner=get_thumb($data->banner);
        
        $this->assign('action',url('update?id='.$id));
        $this->assign('type',$data->type);
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
        $post=$request->post();
        if(isset($post['pos'])){
            $post['pos']=implode(',',$post['pos']);
        }
        $mModel=new mModel();
        $affected=$mModel->allowField(true)->save($post,['id'=>$id]);
        if($affected){
            return [
                'code'=>0
            ];
        }else{
            return [
                'code'=>-1,
                'msg'=>'sql错误:'.$mModel->getError()
            ];
        }
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
        mModel::destroy($id);
        return [
            'code'=>0
        ];
    }
}
