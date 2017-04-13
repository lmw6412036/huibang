<?php

namespace app\common\model;

use think\Model;

class Ad extends Model
{
    //
    protected $autoWriteTimestamp =true;
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'etime';
    
    //
    public function adpos(){
        return $this->belongsTo('Coclass','type');
    }
}
