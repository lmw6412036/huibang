<?php
namespace app\admin\controller;
use think\Controller;
use think\Cookie;
use app\common\model\Admin;
class Base extends Controller{
   public function _initialize(){
       $admin=Cookie::has('admin');
       if($admin){
           $adminUser=Admin::get($admin);
           $this->assign('admin',$adminUser);
       }else{
           $this->redirect('login/login');
       }
       
   }     
}