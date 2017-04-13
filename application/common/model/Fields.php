<?php

namespace app\common\model;

use think\Model;

class Fields extends Model
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
}
