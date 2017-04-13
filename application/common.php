<?php
use app\common\model\Thumb;
use app\common\model\Ad;
use app\common\model\Cate;
use app\common\model\Member;
use app\common\model\Coclass;

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function get_thumb($id){
    if(!$id){return config('static').'/images/noimg.gif';}
    $thumb=Thumb::get($id);
    if(!$thumb){return config('static').'/images/noimg.gif';}
    if($thumb->type=="picture"){
        return config('static')."/../uploads/".$thumb->url;
    }
}

function get_ad($id){
    return $ad=Ad::get($id);
}

function get_cate($id,$field="name"){
    $c=Cate::get($id);
    if (!$c){return $id;}
    return $field?$c->$field:$c;
}

function get_member($id,$field="name"){
    $c=Member::get($id);
    if(!$c){return $id;}
    return $field?$c->$field:$c;
}

function get_coclass($id,$field="name"){
    if(!$id){return $id;}
    $c=Coclass::get($id);
    if (!$c){return $id;}
    return $field?$c->$field:$c;
    
}

function get_coclasses_fid($fid){
    $arr=Coclass::all(function ($query) use ($fid){
        $query->where(['fid'=>$fid]);
    });
    $list=[];
    foreach ($arr as $k=>$v){
        $list[$v->id]=$v->name;
    }
    return $list;
}