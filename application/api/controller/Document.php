<?php
namespace app\api\controller;
use app\common\model\Document as dModel;
use app\common\model\Member as memberModel;
class Document extends Base{
    function lists(){
        $this->init();    
        $p=$this->params;
        $list=new dModel();
        $filter=['model','area','workyear','salary','type','company'];
        foreach ($filter as $v){
            if(isset($p[$v])){
                $$v=$p[$v];
            }else{
                $$v='';
            }
        }
        if(!$model){
            $model='news';
        }
        $list=$list->alias('a');
        $list=$list->field('a.*,b.document_id');
        $list=$list->join(config('database.prefix').'document_'.$model.' b','b.document_id=a.id','left');
        
        $list=$list->where('a.model',$model);
        
        if($area){
            $list=$list->where('b.area',$area);
        }
        if($type){
            $list=$list->where('b.type',$type);
        }
        if($salary){
            $list=$list->where('b.salary',$salary);
        }
        if($workyear){
            $list=$list->where('b.workyear',$workyear);
        }
        if($company){
            $list=$list->where('b.company',$company);
        }
        
        $list=$list->order('a.sort desc')->paginate(10);
        $mD=model('Document_'.ucfirst($model));
        foreach ($list as $v){
            $m=$mD->get(['document_id'=>$v->id]);
            $m->company=get_member($m->company,'name');
            $arr=['sb','bcz','salary','workyear','area'];
            foreach ($arr as $vv){
                $m->$vv=get_coclass($m->$vv);
            }
            $m->type=get_cate($m->type);
            $v->thumb=get_thumb($v->thumb_id);
            $v->$model=$m;
        }
        
        return $this->response([
            'code'=>0,
            'data'=>$list
        ],'json',200);
    }
    
    public function get(){
        $this->init();
        $p=$this->params;
        $id=$p['id'];
        $data=dModel::get($id);
        $model=$data['model'];
        $mD=model('Document_'.ucfirst($model));
        
        $m=$mD->get(['document_id'=>$id]);
        
        $arr=['sb','bcz','salary','workyear','area'];
        foreach ($arr as $vv){
            if(isset($m->$vv)){
                $m->$vv=get_coclass($m->$vv);
            }
        
        }
        
        if($model=="job"){
            $m->company=memberModel::get($m->company);
            $m->type=get_cate($m->type);
        }        
        
        $data->$model=$m;        
        $data->thumb=get_thumb($data->thumb_id);
        return $this->response([
            'code'=>0,
            'data'=>$data
        ],'json',200);
    }
}