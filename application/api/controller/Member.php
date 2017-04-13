<?php
namespace app\api\controller;
use app\common\model\Member as memberModel;
class Member extends Base{
    function getCbp(){
        $this->init();
        $p=$this->params;
        $fields=['type','pos'];
        $list=new memberModel();
        
        foreach ($fields as $v){
            if(isset($p[$v])){
                $$v=$p[$v];
            }else{
                $$v="";
            }
        }
                
        if($type){
            $list=$list->where('type',$type);
        }
        
        if($pos){
            $list=$list->where("find_in_set($pos,pos)");
        }
        
        $list=$list->paginate(10);
        
        foreach ($list as $v){
            $v->thumb_url=get_thumb($v->thumb);
            $v->edu=get_coclass($v->edu);
            $v->workyear=get_coclass($v->workyear);
        }       
        
        return $this->response([
            'code'=>0,
            'data'=>$list
            ]);
    }
    
    function getSzc(){
        $this->init();
        $szctype=$this->params['szctype'];
        $where=[];
        if($szctype){
            $where['szctype']=$szctype;
        }
        $area=$this->params['area'];
        if($area){
            $where['area']=$area;
        }
        
        $type=$this->params['type'];
        if($type){
            $where['type']=$type;
        }
        
        
        $list=memberModel::where($where)->select();
        
        return $this->response([
            'code'=>0,
            'data'=>array_map(function ($item){
                $item['thumb_url']=get_thumb($item['thumb']);
                return $item;
            }, $list)
        ]);
    }
    
    function get(){
        $this->init();
        $id=$this->params['id'];
        $data=memberModel::get($id);
        $data->thumb_url=get_thumb($data->thumb);
        $data->banner=get_thumb($data->banner);
        $field=['szctype','area'];
        foreach ($field as $v){
            $data->$v=get_coclass($data->$v);
        }
        return $this->response(['code'=>0,
            'data'=>$data
        ]);
    }
}