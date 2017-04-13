<?php

namespace app\common\model;

use think\Model;

class Cate extends Model
{
    //
    protected $autoWriteTimestamp =true;
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'etime';
    protected $auto=['sort'];
    
    public function setSortAttr($value){
        return $value?$value:time();
    }
    //数组显示
    public function lists($type,$fid=0,$level=0){
        $arr=$this->all(function ($query) use ($type,$fid){
           $query->where(['type'=>$type,'fid'=>$fid])->order('sort desc'); 
        });
        //$arr=$arr->toArray();
        //dump($arr);exit();
        $list=[];
        foreach ($arr as $k=>$v){
            $v->level=$level;
            $list[]=$v;
            if($this->get(['fid'=>$v->id,'type'=>$type])){
                $list=array_merge($list,$this->lists($type,$v->id,$level+1));
            }
        }
        //dump($list);
        return $list;
    }
}
