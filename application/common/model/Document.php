<?php

namespace app\common\model;

use think\Model;

class Document extends Model
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
    
    public function job(){
        return $this->hasOne('DocumentJob','document_id');
    }
    
    public function add($data){
        $model=$data['model'];
        $affected=$this->allowField(true)->save($data);
        if($affected){
            $data['document_id']=$this->id;
            try {
                $m=model('Document'.ucfirst($model));
            }
            catch (\Exception $e){
                return $e->getMessage();
            }
            try {
                $a=$m->allowField(true)->save($data);
                if(!$a){
                    $error=$m->getError();
                    $this->destroy($this->id);
                    return $error;
                }
                return true;
            }catch (\Exception $e){
                $this->destroy($this->id);
                return $e->getMessage();
            }
        }else{
            $error=$this->getError();
            $this->destroy($this->id);
            return $error;
        }
    }
    
    public function up($id,$data){
        $model=$data['model'];
        $this->allowField(true)->save($data,['id'=>$id]);
        $d=model('Document'.ucfirst($model));
        $d->allowField(true)->save($data,['document_id'=>$id]);
        return true;       
    }
}
