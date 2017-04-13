<?php
namespace app\api\controller;
use think\Db;
class Sql extends Base{
    function update(){
        $this->init();
        $p=$this->params;
        $data=$p;
        if(isset($p['table'])){
            $db=Db::name($p['table']);
            unset($data['table']);
        }
        if(isset($p['id'])){
            $db=$db->where('id',$p['id']);
            unset($data['id']);
        }
        $db->update($data);
        return ['code'=>0];
    }    
}