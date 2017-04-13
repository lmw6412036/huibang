<?php

namespace app\admin\controller;

use think\Request;
use app\common\model\Coclass;
use app\common\model\Menu as menuModel;

class Menu extends Base
{
    public function _initialize(){
        $cates=Coclass::all(function ($query){
            $query->where([
                'fid'=>2//后台菜单分类
            ]);
        });
        $cateArr=[];
        foreach ($cates as $k=>$v){
            $cateArr[$v->id]=$v->name;
        }
        $this->assign('cates',$cateArr);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $list=menuModel::all(function ($query){
            $query->where('status',1)->order('cate');
        });
        $this->assign('list',$list);
        $this->assign('count',count($list));
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
        $menuM=new menuModel;
        $menuM->data($post);
        $affected = $menuM->allowField(true)->save();
        if ($affected) {
            return [
                'code' => 0
            ];
        } else {
            return [
                'code' => - 1,
                'msg' => 'sql错误:' . $menuM->getError()
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
        $this->assign('action',url('update?id='.$id));
        $data=menuModel::get($id);
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
        $menuM=new menuModel;
        $id=$menuM->allowField(true)->save($post,['id'=>$id]);
        if (!$id){
            //diylog($adminModel);
            return [
                'code'=>-1,
                'msg'=>'sql错误：'.$menuM->getError()];
        }
        return [
            'code'=>0,
            'data'=>$menuM->getData()
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
        menuModel::destroy($id);
        return [
            'code'=>0
        ];
    }
}
