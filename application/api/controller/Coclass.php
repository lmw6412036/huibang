<?php
namespace app\api\controller;
class Coclass extends Base{
    function getByFid(){
        $this->init();
        $fid=$this->params['fid'];
        return $this->response([
            'code'=>0,
            'data'=>get_coclasses_fid($fid)
        ]);
    } 
}