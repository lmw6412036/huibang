<?php

namespace app\common\model;

use think\Model;

class Menu extends Model
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
    
    //关联模型
    public function menucate(){
        return $this->belongsTo('Coclass','cate');
    }
    
    
}
