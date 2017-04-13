<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
class Index extends Controller
{
    public function _initialize(){
        $request=Request::instance();
        $agent=$request->header('user-agent');
        //dump($agent);
        $agent=strtolower($agent);
        if(stristr($agent, 'android')||stristr($agent, 'iphone')){
            //$request->controller('m');
            $url=$request->url(true);
            //dump($url);
            if(stristr($url, '/index/index/')){
                $url=str_ireplace('/index/index/', '/index/m/',$url);
                $this->redirect($url);
            }else{
                $this->redirect('index/m/index');
            }
            
        }else{
            
        }
    }
    /**
     * 
     */
    public function index()
    {
        return $this->fetch();
    }
    
    public function lists(){
        return $this->fetch();
    }
    
    public function detail(){
        return $this->fetch();
    }
}
