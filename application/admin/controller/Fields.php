<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Coclass;
use think\Db;
use app\common\model\Fields as mFields;

class Fields extends Controller
{
    public function _initialize(){
        //需要自定义维护的数据表
        $c=Coclass::all(function ($query){
            $query->where([
                'fid'=>34
            ]);
        });
        $arr=[];
        foreach ($c as $k=>$v){
            $arr[$v->title]=$v->name;
        }
        $this->assign('tables',$arr);
        
        /* 字段类型 */
        $fieldtypes=Coclass::all(function ($query){
            $query->where([
                'fid'=>15
            ]);
        });
        $arr=[];
        foreach ($fieldtypes as $value){
            $arr[$value['title']]=$value['name'];
        }
        $this->assign('fieldtypes',$arr);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $list=mFields::where([])->order('table_name')->paginate(10);
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
        $this->assign('action',url('save'));
        $this->assign('data',[]);
        return $this->fetch('save');
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
        $post=$request->post();
        $mf=new mFields;
        $mf->data($post);
        $affected=$mf->allowField(true)->save();
        if($affected){
            $table=config('database.prefix').$post['table_name'];
            $type=getFieldType($post['type']);
            try {
                Db::execute("alter table $table add ".$post['title']." $type;");
            }
            catch (\Exception $e){
                mFields::destroy($mf->id);
                return getErrorCode(605,$e->getMessage());
            }
            return ['code'=>0];
        }else{
            return [
                'code' => - 1,
                'msg' => 'sql错误:' . $mf->getError()
            ];
        }
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
        $data=mFields::get($id);
        $this->assign('action',url('update?id='.$id));
        $this->assign('data',$data);
        return $this->fetch('save');
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
        $post=$request->post();
        $field=mFields::get($id);
        $mf=new mFields;
        $affected=$mf->allowField(true)->save($post,['id'=>$id]);
        if (!$affected){
            //diylog($adminModel);
            return [
                'code'=>-1,
                'msg'=>'sql错误：'.$mf->getError()];
        }
        
        if($post['title']!=$field['title']||$post['type']!=$field['type']){
            $field1=$field['title'];
            $field2=$post['title'];
            $table=config("database.prefix").$field['table_name'];
            $type=getFieldType($post['type']);
            $sql="alter table $table change $field1 $field2 $type";
            try {
                Db::execute($sql);
            }
            catch (\Exception $e){
                return [
                    'code'=>-1,
                    'msg'=>$e->getMessage()
                ];
            }
        };
        
        return [
            'code'=>0,
            'data'=>$mf->getData()
        ];
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
        $field=mFields::get($id);
        $table=config("database.prefix").$field['table_name'];
        $sql="alter table $table drop ".$field['title'];
        try {
            Db::execute($sql);
        }catch (\Exception $e){
            return [
                'code'=>-1,
                'msg'=>$e->getMessage()
            ];
        }
        mFields::destroy($id);
        return ['code'=>0];
    }
}
