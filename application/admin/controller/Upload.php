<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Thumb;

class Upload extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request,$callback)
    {
        //
        $params=$request->param();
        $get=$request->get();
        
        $this->assign('callback',$callback);
        $list=Thumb::where('1=1');
        
        if(isset($params['cate'])){
            $this->assign('cate',$params['cate']);
            $list=$list->where('cate',$params['cate']);
        }
        
        
        $list=$list->order('id desc')->paginate(5);
        $this->assign('list',$list);
        return $this->fetch();
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
        $file = $request->file('file');
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $url=$info->getSaveName();
            // 成功上传后 获取上传信息
            // 输出 jpg
            $params=$request->param();
            $post=$request->post();
            if(isset($params['onlyup'])){
                $res=[
                    'code'=>0,
                    'url'=>$url
                ];
            }else{
                $thumbM=new Thumb;
                $data=[
                    'url'=>$url
                ];
                
                if(isset($post['name'])){
                    $data['name']=$post['name'];
                }
                
                if(isset($post['cate'])){
                    $data['cate']=$post['cate'];
                }
                
                $thumbM->data($data);
                $affected=$thumbM->allowField(true)->save();
                if($affected){
                    $res= [
                        'code'=>0,
                        'data'=>$url
                    ];
                }else{
                    $res= [
                        'code'=>-1,
                        'msg'=>$thumbM->getError()
                    ];
                }    
            }
        }else{
            // 上传失败获取错误信息
            $res= getErrorCode(607,$file->getError());
        }
        return json_encode($res);
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
        Thumb::destroy($id);
        return ['code'=>0];
    }
}
