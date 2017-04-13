<?php

namespace app\common\model;

use think\Model;

class Admin extends Model
{
    protected $autoWriteTimestamp =true;
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'etime';
    protected $auto = ['pass'];
    //
    public function role(){
        return $this->belongsTo('AdminRole','role_id');
    }
    //
    public function setPassAttr($value){
        return md5($value);   
    }
}
