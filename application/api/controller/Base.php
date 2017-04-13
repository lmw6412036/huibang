<?php
namespace app\api\controller;
use think\controller\Rest;
use think\Request;
class Base extends Rest{
    public $params;
    public function  init(){
        header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type");
        $this->getParams();
        if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
            exit();
        }
    }
    
    public function getParams(){
        $get=request()->get();
        $post=request()->post();
        $json=request()->getInput();
        $json=json_decode($json,true);
        $params=array();
        if ($get){
            $params=array_merge($params,$get);
        }
        if ($post){
            $params=array_merge($params,$post);
        }
        if ($json){
            if(isset($json['page'])){
                request()->request(['page'=>$json['page']]);
            }
            $params=array_merge($params,$json);
        }
        $this->params=$params;
    }
    
}