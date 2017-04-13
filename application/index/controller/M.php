<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\common\model\Ad;
use app\common\model\Member;
use app\common\model\DocumentJob;
use app\common\model\DocumentNews;
use app\common\model\Cate;
use think\Model;
use app\common\model\Document;

class M extends Controller
{
    public function _initialize(){
        $request=Request::instance();
        $agent=$request->header('user-agent');
        //dump($agent);
        $agent=strtolower($agent);
        if(stristr($agent, 'android')||stristr($agent, 'iphone')){
            
        }else{
            $url=$request->url(true);
            if(stristr($url, '/m/')){
                $url=str_ireplace('/m/', '/index/', $url);
                $this->redirect($url);  
            }else{
                $this->redirect('index/index/index');
            }
            
        }
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }
    //找工作
    public function job(){
        return $this->fetch();
    }
    //找人才
    public function people(){
        return $this->fetch();
    }
    //详情
    public function detail($t){
        return $this->fetch('m/detail/'.$t);
    }
    
        

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
