<?php
namespace app\api\controller;
use app\common\model\Ad as adModel;
class Ad extends Base{
    function lists(){
        $this->init();    
        $p=$this->params;
        $list=adModel::all(function ($query) use($p){
            $query->where([
                'type'=>$p['type']
            ]);            
        });
        return $this->response([
            'code'=>0,
            'data'=>array_map(function ($value){
                if($value['thumb_id']){
                    $value['thumb_url']=get_thumb($value['thumb_id']);
                }
                return $value;
            },$list)
        ],'json',200);
    }
}