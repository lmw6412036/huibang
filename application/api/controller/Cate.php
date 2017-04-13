<?php
namespace app\api\controller;
use app\common\model\Cate as cateModel;
class Cate extends Base{
    function lists(){
        $this->init();    
        $p=$this->params;
        if(!$p['type']){
            $p['type']="job";
        }
        $cate=new cateModel();
        $list=$cate->lists($p['type']);
         
        return $this->response([
            'code'=>0,
            'data'=>$list
        ],'json',200);
    }
}