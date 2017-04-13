<?php
namespace app\admin\controller;

use app\common\model\Config;
use think\Cookie;
use app\common\model\Menu;
class Index extends Base
{
    
    public function index(){
       $list=Menu::all();
       $this->assign('list',$list);
       //dump($list);exit();
       $cates=[];
       $catenames=[];
       $cateArr=[];
       foreach ($list as $k=>$v){
           if(!in_array($v->cate,$cates)){
               $cates[]=$v->cate;
               $catenames[]=$v->menucate->name;
           }
       }
       foreach ($catenames as $key=>$value){
           $cateArr[]=[
               'cate'=>$cates[$key],
               'name'=>$value
           ];
       }
       $this->assign('cate',$cateArr);
       return $this->fetch();
    }
    public function welcome(){
        $config=Config::get(1);
        $this->assign('config',$config);
        return $this->fetch();
    }
    
    public function upload(){
        return $this->fetch();
    }
}
