<?php

namespace app\common\model;

use think\Model;

class AdminRole extends Model
{
    protected $autoWriteTimestamp =true;
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'etime';
    
}
