<?php
namespace app\admin\controller;

use think\Request;
use app\common\model\Coclass as coclassModel;
use app\common\model\Menu;

class Coclass extends Base
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        //
        $list = coclassModel::where([
            'fid' => 0
        ])->paginate(10);
        $this->assign('list', $list);
        $this->assign('count', $list->total());
        return $this->fetch();
    }
    
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function lists(Request $request,$fid)
    {
        //
        $list=coclassModel::all(function ($query) use($fid){
           $query->where([
               'fid'=>$fid
           ]); 
        });
        $this->assign('list', $list);
        $this->assign('fid',$fid);
        $this->assign('count', count($list));
        return $this->fetch('lists');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create($fid = 0)
    {
        //
        $this->assign('data', []);
        $this->assign('fid',$fid);
        
        if($fid){
            $coclass=coclassModel::get($fid);
            //diylog($coclass,'coclass');
            $this->assign('fname',$coclass->name);
        }
        
        
        $this->assign('action', url('save?fid=' . $fid));
        return $this->fetch('save');
    }

    /**
     * 保存新建的资源
     *
     * @param \think\Request $request            
     * @return \think\Response
     */
    public function save(Request $request, $fid)
    {
        //
        // diylog($fid,'fid');
        $coclassModel = new coclassModel();
        $post = $request->post();
        $coclassModel->data($post);
        $affected = $coclassModel->allowField(true)->save();
        if ($affected) {
            return [
                'code' => 0
            ];
        } else {
            return [
                'code' => - 1,
                'msg' => 'sql错误:' . $coclassModel->getError()
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
    public function edit($id)
    {
        //
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
        coclassModel::destroy($id);
        Menu::destroy(function ($query) use($id){
            $query->where([
                'cate'=>$id
            ]);
        });
        return [
            'code'=>0
        ];
    }
}
