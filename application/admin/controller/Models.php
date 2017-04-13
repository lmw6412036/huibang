<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\common\model\Models as modelM;
use app\common\model\ModelFields;
use app\common\model\Coclass;
use think\Model;

class Models extends Controller
{
    /**
     * 初始化
     * @see \think\Controller::_initialize()
     */
    public function _initialize(){
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
     * 是否存在模型表
     * @param unknown $table
     */
    private function hastable($table){
        $tables=Db::query('show tables');
        $hasTable=false;
        $tableName=config('database.prefix').'document_'.$table;
        foreach ($tables as $value){
            if($value['Tables_in_'.config('database.database')]==$tableName){
                $hasTable=true;
            }
        }
        return $hasTable;
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //dump(config('database.prefix'));
        //
        $list=modelM::where([])->paginate(10);
        $this->assign('count',$list->total());
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 创建表
     * @param Request $request
     */
    public function maketable(Request $request){
        $post=$request->post();
        $title=$post['title'];
        $hasTable=$this->hastable($title);
        if($hasTable){
            return getErrorCode(604);
        }else{
            $sql="CREATE TABLE ".config('database.prefix')."document_".$title."(
            id INT(11) NOT NULL AUTO_INCREMENT,
            document_id INT,
            PRIMARY KEY(id)
            )";
            try {
                Db::execute($sql);
            }catch (\Exception $e){
                return getErrorCode(605,$e->getMessage());
            }
            
            return ['code'=>0];
        }
        
    }
    /**
     * 字段列表
     * @param 模型 $id
     */
    public function fields($id){
        $list=ModelFields::all(function ($query) use ($id){
            $query->where([
                'model_id'=>$id
            ]);
        });
        $this->assign('count',count($list));
        $this->assign('list',$list);
        
        $model=modelM::get($id);
        $this->assign('table',$model);
        
        return $this->fetch();
    }
    
    /**
     * 添加字段
     * @param 模型 $id
     */
    public function addfield($id){
        $this->assign('action',url('savefield'));
        $this->assign('data',[]);
        
        $model=modelM::get($id);
        $this->assign('table',$model);
        
        
        
        return $this->fetch();
    }
    /**
     * 保存字段
     * @param Request $request
     * @return multitype:unknown string |multitype:number |multitype:number string
     */
    public function savefield(Request $request){
        $post=$request->post();
        if(!$this->hastable($post['model_title'])){
            return getErrorCode(606);
        }
        $mf=new ModelFields;
        $mf->data($post);
        $affected=$mf->allowField(true)->save();
        if($affected){
            $table=config('database.prefix')."document_".$post['model_title'];
            $type=getFieldType($post['type']);
            try {
                Db::execute("alter table $table add ".$post['title']." $type;");
            }
            catch (\Exception $e){
                ModelFields::destroy($mf->id);
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
     * 删除字段
     * @param unknown $id
     */
    public function deletefield($id){
        $field=ModelFields::get($id);
        $table=$table=config('database.prefix')."document_".$field['model_title'];
        try {
            Db::execute("alter table $table drop ".$field['title']);
        }catch (\Exception $e){
            return getErrorCode(605,$e->getMessage());
        }     
        ModelFields::destroy($id);
        return ['code'=>0];
    }
    /**
     * 编辑字段
     * @param unknown $id
     */
    public function editfield($id){
        $field=ModelFields::get($id);
        $table=modelM::get($field['model_id']);
        $this->assign('table',$table);
        $this->assign('data',$field);
        $this->assign('action',url('updatefield?id='.$id));
        return $this->fetch('addfield');
    }
    
    public function updatefield(Request $request,$id){
        $post=$request->post();
        $field=ModelFields::get($id);
        $mf=new ModelFields;
        $affected=$mf->allowField(true)->save($post,['id'=>$id]);
        if (!$affected){
            //diylog($adminModel);
            return [
                'code'=>-1,
                'msg'=>'sql错误：'.$mf->getError()];
        }
        
        if($post['title']!=$field['title']){
            $field1=$field['title'];
            $field2=$post['title'];
            $table=config("database.prefix")."document_".$field['model_title'];
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
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $this->assign('data',[]);
        $this->assign('action',url('save'));
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
        /*
         * CREATE TABLE jbh_document_news(
            id INT(11) NOT NULL AUTO_INCREMENT,
            document_id INT,
            PRIMARY KEY(id)
            )
         * */
        $post=$request->post();
        $modelM=new modelM();
        $modelM->data($post);
        $affected = $modelM->allowField(true)->save();
        if ($affected) {
            return [
                'code' => 0
            ];
        } else {
            return [
                'code' => - 1,
                'msg' => 'sql错误:' . $modelM->getError()
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
        $data=modelM::get($id);
        $this->assign('data',$data);
        $this->assign('action',url('update?id='.$id));
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
        $table=modelM::get($id);
        $m=new modelM;
        $affected=$m->allowField(true)->save($post,['id'=>$id]);
        if ($affected) {
            $table1=config('database.prefix')."document_".$table['title'];
            $table2=config('database.prefix')."document_".$post['title'];
            $table3=config('database.prefix')."model_fields";
            if($table2!=$table1){
                $sql="alter table $table1 rename to $table2;";
                $sql2="update $table3 set model_title='".$post['title']."' where model_title='".$table['title']."';";
                try {
                    Db::execute($sql);
                    Db::execute($sql2);
                }catch (\Exception $e){
                    return [
                        'code'=>-1,
                        'msg'=>$e->getMessage()
                    ];
                }
                
            }
            
            return [
                'code' => 0
            ];
        } else {
            return [
                'code' => - 1,
                'msg' => 'sql错误:' . $m->getError()
            ];
        }
        
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
        $model=modelM::get($id);
        if ($this->hastable($model->title)){
            Db::execute('DROP TABLE '.config('database.prefix').'document_'.$model->title);
        }
        modelM::destroy($id);
        return [
            'code'=>0
        ];
    }
    
}
