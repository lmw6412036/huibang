<?php
namespace app\api\controller;
class Thumb extends Base{
    function get(){
        $this->init();
        $p=$this->params;
        if($p['id']){
            return $this->response([
                'code'=>0,
                'data'=>get_thumb($p['id'])
            ]);
        }
        
    }
}