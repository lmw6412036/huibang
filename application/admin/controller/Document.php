<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Document as dModel;
use think\Db;

class Document extends Controller
{
    public function _initialize(){
        $this->assign('sb',get_coclasses_fid(43));
        $this->assign('bcz',get_coclasses_fid(47));
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        //
        $model=$request->param('model');
        $this->assign('model',$model);
        $list=dModel::where(function ($query) use ($model){
           $query->where('model',$model);
        })->order('sort desc')->paginate(10);
        $mD=model('Document_'.ucfirst($model));
        foreach ($list as $v){
            $v->$model=$mD->get(['document_id'=>$v->id]);
        }
        $this->assign('list',$list);
        return $this->fetch($model);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        //
        $this->assign('action',url('save'));
        $this->assign('data',[]);
        
        $model=$request->param('model');
        $this->assign('model',$model);
        
        if($model=='job'){
            $this->assign('type',"");
            $this->assign('company',"");
            $this->assign('typetext',"");
            $this->assign('companytext',"");
        }
        elseif($model=="news"){
            
        }
        
        
        
        
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
        $dM=new dModel;
        $res=$dM->add($data);
        if($res===true){
            return ['code'=>0];
        }else{
            return ['code'=>-1,'msg'=>$res];
        };
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
        $data=dModel::get($id);
        $d=model('Document'.ucfirst($data->model));
        $data2=$d->get(['document_id'=>$id]);
        $data2=($data2->toArray());
        foreach ($data2 as $k=>$v){
            $data->$k=$v;
        }
        
        if($data->model=='job'){
            $this->assign('type',$data->type);
            $this->assign('company',$data->company);
            $this->assign('typetext',get_cate($data->type));
            $this->assign('companytext',get_member($data->company));
        }
        elseif ($data->model=="news"){
            $this->assign('cate',$data->cate);
            $this->assign('catename',get_cate($data->cate));
        }
        
        
        
        $this->assign('model',$data->model);
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
        $d=new dModel;
        $affected=$d->up($id, $data);
        if($affected===true){
            return ['code'=>0];
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
        $document=dModel::get($id);
        $d=model('Document'.ucfirst($document->model));
        dModel::destroy($id);
        $d->where('document_id',$id)->delete();
        return ['code'=>0];
        
    }
    
    public function daoru(Request $request){
        $param=$request->param();
        $this->assign('model',$param['model']);
        
        if(isset($param['type'])){
            
        }
        
        $get=$request->get();
        if(isset($get['page'])){
            $page=$get['page'];
        }else{
            $count=Db::name('sheet2')->where('daoru',1)->count();
            $page=$count;
        }
        if($page<=0){$page=1;}
        $this->assign('page',$page);
        $data=Db::name('sheet2')->limit(($page-1).',1')->select();
        if(!$data){
            return "没有了";
        }
        $data=$data[0];
        $com=Db::query("SELECT * FROM 
                        jbh_member a 
                        JOIN 
                        (SELECT ROUND(MAX(c.id)*RAND()) rid FROM `jbh_member` c WHERE c.type='company') b 
                        ON a.id>=b.rid 
                        WHERE a.type='company'
                        LIMIT 1");
        $this->assign('company',$com[0]['id']);
        $this->assign('companytext',$com[0]['name']);
        
        
        $this->assign('data',$data);
        $this->assign('action',url('save'));
        return $this->fetch();
    }
}
