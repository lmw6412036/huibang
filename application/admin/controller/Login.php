<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\Config;
use think\Request;
use app\common\model\Admin;
use think\Cookie;
class Login extends Controller{
    public function login(Request $request){
        if($request->isPost()){
            $post=$request->post();
            $captcha=$post['captcha'];
            $name=$post['name'];
            $pass=$post['pass'];
            
            if(!captcha_check($captcha)){
                return getErrorCode(601);
            }
            
            $admin=Admin::get(['name'=>$name]);
            if(!$admin){
                return getErrorCode(602);
            }
            
            if($admin['pass']!=md5($pass)){
                return getErrorCode(603);
            }
            // 设置Cookie 有效期为 3600秒
            Cookie::set('admin',$admin['id'],3600*24);
            return ['code'=>0];
        }
        //dump($admin->role);
        //trace($admin->role);        
    	$config=Config::get(1);
        $this->assign('config',$config);
    	return $this->fetch();
    }
}