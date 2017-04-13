<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Cate as mCate;

class Cate extends Controller
{
    public function _initialize(){
        $d=new mCate();
        $type=request()->param('type');
        $list=$d->lists($type);
        $this->assign('total',count($list));
        $this->assign('list',$list);
        $this->assign('pos',get_coclasses_fid(65));
    }
    
    public function select(){
        return $this->fetch();
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        //

        $model = $request->param('type');
        $this->assign('type', $model);
        //;
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        //
        $fid = $request->param('fid', 0);
        if($fid){
            $f=mCate::get($fid);
            $this->assign('fname',$f->name);
        }
        
        $type = $request->param('type', '');
        
        $this->assign('action', url('save', [
            'fid' => $fid,
            'type' => $type
        ]));
        
        $this->assign('fid',$fid);
        $this->assign('type',$type);
        $this->assign('data', []);
        return $this->fetch('save');
    }

    /**
     * 保存新建的资源
     *
     * @param \think\Request $request            
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $data = $request->post();
        $data['fid'] = $request->param('fid');
        if(isset($data['pos'])){
            $data['pos']=implode(',',$data['pos']);
        }
        $d = new mCate();
        $affected = $d->allowField(true)->save($data);
        if ($affected) {
            return [
                'code' => 0
            ];
        } else {
            return [
                'code' => - 1,
                'msg' => 'sql错误:' . $d->getError()
            ];
        }
    }

    /**
     * 显示指定的资源
     *
     * @param int $id            
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id            
     * @return \think\Response
     */
    public function edit(Request $request,$id)
    {
        //
        $d=new mCate();
        $fid = $request->param('fid', 0);
        $type = $request->param('type', '');
        if($fid){
            $f=$d->get($fid);
            $this->assign('fname',$f->name);
        }else{
            $this->assign('fname','');
        }
        
        
        
        $this->assign('action', url('update', [
            'id'=>$id,
            'fid' => $fid,
            'type' => $type
        ]));
        
        
        
        
        $data=$d->get($id);
        $this->assign('fid',$fid);
        $this->assign('type',$type);
        $this->assign('data', $data);
        return $this->fetch('save');
    }

    /**
     * 保存更新的资源
     *
     * @param \think\Request $request            
     * @param int $id            
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $request->post();
        if(isset($data['pos'])){
            $data['pos']=implode(',',$data['pos']);
        }
        $d = new mCate();
        $affected = $d->allowField(true)->save($data,['id'=>$id]);
        if ($affected) {
            return [
                'code' => 0
            ];
        } else {
            return [
                'code' => - 1,
                'msg' => 'sql错误:' . $d->getError()
            ];
        }
    }

    /**
     * 删除指定资源
     *
     * @param int $id            
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        mCate::destroy($id);
        return ['code'=>0];
    }
}
