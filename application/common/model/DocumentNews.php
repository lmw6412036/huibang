<?php

namespace app\common\model;

use think\Model;

class DocumentNews extends Model
{
    //
    public function lists($param) {
        
    }
    
    public function document(){
        return $this->belongsTo('Document','document_id');
    }
}
