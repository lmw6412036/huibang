<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Coclass;
use app\common\model\Ad as adModel;

class Ad extends Controller
{
    public function _initialize(){
        $arr=Coclass::all(function ($query){
            $query->where([
                'fid'=>27
            ]);
        });
        //广告位位置
        $pos=[];
        foreach ($arr as $k=>$v){
            $pos[$v['id']]=$v['name'];
        }
        $this->assign('pos',$pos);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $list=adModel::where([])->order('type')->paginate(10);
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
        $this->assign('action',url('save'));
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
        $adM=new adModel;
        $adM->data($post);
        $affected=$adM->allowField(true)->save();
        if($affected){
            return [
                'code'=>0
            ];
        }else{
            return [
                'code'=>-1,
                'msg'=>'sql错误:'.$adM->getError()
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
        $data=adModel::get($id);
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
        $adM=new adModel;
        //$adM->data($post);
        $affected=$adM->allowField(true)->save($post,['id'=>$id]);
        if($affected){
            return [
                'code'=>0
            ];
        }else{
            return [
                'code'=>-1,
                'msg'=>'sql错误:'.$adM->getError()
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
        adModel::destroy($id);
        return ['code'=>0];
    }
}
