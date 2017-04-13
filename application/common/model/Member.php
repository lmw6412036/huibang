<?php
namespace app\common\model;

use think\Model;

class Member extends Model
{

    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'ctime';

    protected $updateTime = 'etime';

    protected $auto = [
        'pass','sort'
    ];
    //
    public function setPassAttr($value)
    {
        return md5($value);
    }
    public function setSortAttr($value){
        return $value?$value:time();
    }
}
