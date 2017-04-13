<?php
use think\Model;
use app\common\model\Coclass;
// 错误提示
function getErrorCode($code, $msg = '')
{
    $error = [
        601 => '验证码错误',
        602 => '用户不存在',
        603 => '密码错误',
        604=>'表已存在',
        605=>'创建表失败',
        606=>'请先创建表',
        607=>'上传失败'
    ];
    return [
        'code' => $code,
        'msg' => $msg ? $msg : $error[$code]
    ];
}

function diylog($data, $title = '')
{
    if (is_object($data)) {
        $data = $data->toArray();
    }
    think\Log::record($title, 'debug');
    think\Log::record($data, 'debug');
}

// 模板函数
/**
 *
 * @param unknown $label            
 * @param unknown $name            
 * @param string $data            
 * @param string $placeholder            
 * @param string $type            
 * @param unknown $option            
 * @return string
 */
function admin_tpl_input($label, $name, $data = "", $placeholder = "", $type = 'text', $option = [])
{
    $required = false;
    $readonly=false;
    $labelW = [
        4,
        3
    ];
    $contrlW = [
        8,
        9
    ];
    $id = $name;
    $class = '';
    
    if ($option) {
        $fields = [
            'required',
            'readonly'
        ];
        foreach ($fields as $v) {
            if (isset($option[$v])) {
                $$v = $option[$v];
            }
        }
    }
    
    // trace($option);
    $value = "";
    
    if (! $data) {
        $value = "";
    } elseif (is_array($data)) {
        if (isset($data[$name])) {
            $value = $data[$name];
        }
        ;
    } elseif (is_object($data)) {
        if (isset($data->$name)) {
            $value = $data->$name;
        }
    } else {
        $value = $data;
    }
    
    return '
        <label class="form-label col-xs-' . $labelW[0] . ' col-sm-' . $labelW[1] . '">
        ' . ($required ? '<span class="c-red">*</span>' : '') . $label . '：</label>
		<div class="formControls col-xs-' . $contrlW[0] . ' col-sm-' . $contrlW[1] . '">
			<input '.($readonly?'readonly':'').' '. ($required ? 'required' : '') . ' type="' . $type . '" class="input-text" value="' . $value . '" placeholder="' . $placeholder . '" id="' . $id . '" name="' . $name . '">
		</div>';
}

/**
 *
 * @param unknown $label            
 * @param unknown $name            
 * @param string $value            
 * @param unknown $data            
 * @param unknown $option            
 * @return string
 */
function admin_tpl_radio($label, $name, $value = '', $data = [], $option = [])
{
    $required = false;
    $labelW = [
        4,
        3
    ];
    $contrlW = [
        8,
        9
    ];
    $id = $name;
    $class = '';
    
    if ($option) {
        $fields = [
            'required'
        ];
        foreach ($fields as $v) {
            if (isset($option[$v])) {
                $$v = $option[$v];
            }
        }
    }
    if (! $value) {
        $value = "";
    } elseif (is_array($value)) {
        if (isset($value[$name])) {
            $value = $value[$name];
        }
    } elseif (is_object($value)) {
        if (isset($value->$name)) {
            $value = $value->$name;
        }
    }
    
    $radios = '';
    foreach ($data as $key => $val) {
        $radios .= '
            <div class="radio-box">
				<input ' . ($key == $value ? 'checked' : '') . ' value="' . $key . '" name="' . $name . '" type="radio" id="' . $name . '-' . $key . '">
				<label for="' . $name . '-' . $key . '">' . $val . '</label>
			</div>
            ';
    }
    
    return '<label class="form-label col-xs-' . $labelW[0] . ' col-sm-' . $labelW[1] . '">
        ' . ($required ? '<span class="c-red">*</span>' : '') . '
                    ' . $label . '：</label>
		<div class="formControls col-xs-' . $contrlW[0] . ' col-sm-' . $contrlW[1] . ' skin-minimal">
			' . $radios . '
		</div>';
}

/**
 *
 * @param unknown $label            
 * @param unknown $name            
 * @param string $value            
 * @param unknown $data            
 * @param unknown $option            
 * @return string
 */
function admin_tpl_select($label, $name, $value = '', $data = [], $option = [])
{
    $required = false;
    $labelW = [
        4,
        3
    ];
    $contrlW = [
        8,
        9
    ];
    $id = $name;
    $class = '';
    $width = "150px";
    
    if ($option) {
        $fields = [
            'required'
        ];
        foreach ($fields as $v) {
            if (isset($option[$v])) {
                $$v = $option[$v];
            }
        }
    }
    
    if (! $value) {
        $value = "";
    } elseif (is_array($value)) {
        if (isset($value[$name])) {
            $value = $value[$name];
        }
    } elseif (is_object($value)) {
        if (isset($value->$name)) {
            $value = $value->$name;
        }
    }
    
    $options = "";
    foreach ($data as $key => $val) {
        $options .= '
            <option ' . ($value == $key ? 'selected' : '') . ' value="' . $key . '">' . $val . '</option>
            ';
    }
    
    return '
        <label class="form-label col-xs-' . $labelW[0] . ' col-sm-' . $labelW[1] . '">
            ' . ($required ? '<span class="c-red">*</span>' : '') . '
            ' . $label . '：</label>
		<div class="formControls col-xs-' . $contrlW[0] . ' col-sm-' . $contrlW[1] . '"> 
            <span class="select-box" style="width:' . $width . '">
			<select class="select" name="' . $name . '" size="1">
				' . $options . '
			</select>
			</span> </div>
        ';
}

/**
 * 
 * @param unknown $label
 * @param unknown $name
 * @param string $value
 * @param unknown $option
 * @return string
 */
function admin_tpl_textarea($label, $name, $value = "", $option = [])
{
    $required = false;
    $labelW = [
        4,
        3
    ];
    $contrlW = [
        8,
        9
    ];
    $id = $name;
    $class = '';
    $placeholder = "";
    $total = 100;
    
    if (! $value) {
        $value = "";
    } elseif (is_array($value)) {
        if (isset($value[$name])) {
            $value = $value[$name];
        }
    } elseif (is_object($value)) {
        if (isset($value->$name)) {
            $value = $value->$name;
        }
    }
    
    if ($option) {
        $fields = [
            'required',
            'total',
            'placeholder'
        ];
        foreach ($fields as $v) {
            if (isset($option[$v])) {
                $$v = $option[$v];
            }
        }
    }
    
    return '<label class="form-label col-xs-' . $labelW[0] . ' col-sm-' . $labelW[1] . '">
        ' . ($required ? '<span class="c-red">*</span>' : '') . '
            ' . $label . '：</label>
		<div class="formControls col-xs-' . $contrlW[0] . ' col-sm-' . $contrlW[1] . '">
			<textarea name="' . $name . '" cols="" rows="" class="textarea"  placeholder="' . $placeholder . '" dragonfly="true" onKeyUp="textarealength(this,' . $total . ')">' . $value . '</textarea>
			<p class="textarea-numberbar"><em class="textarea-length">0</em>/' . $total . '</p>
		</div>';
}

/**
 * 
 * @param unknown $label
 * @param unknown $name
 * @param string $value
 * @param array $option
 * @return string
 */
function admin_tpl_upload($label,$name,$value='',$option=[]){
    $required=false;
    $labelW = [
        4,
        3
    ];
    $contrlW = [
        8,
        9
    ];
    $thumb_id="";
    if(is_array($value)&&isset($value[$name])){
        $thumb_id=$value[$name];
    }elseif(is_object($value)&&isset($value->$name)){
        $thumb_id=$value->$name;
    }elseif (is_string($value)){
        $thumb_id=$value;
    }
    if($thumb_id){
        $pic=get_thumb($thumb_id);   
    }else{
        $pic="";
    }
    
    $domid="id_".uniqid();
    $callback="upload_back_".uniqid();
    $path=config('static');
    $error=$path.'/images/noimg.gif';
    $params=[
        'callback'=>$callback
    ];
    
    $field=['cate'];
    foreach ($field as $k){
        if(isset($option[$k])){
            $params[$k]=$option[$k];
        }
    }
    $uploadUrl=url('upload/index',$params);
    return '<label class="form-label col-xs-' . $labelW[0] . ' col-sm-' . $labelW[1] . '">
        ' . ($required ? '<span class="c-red">*</span>' : '') . '
            ' . $label . '：</label>
                <div class="formControls col-xs-' . $contrlW[0] . ' col-sm-' . $contrlW[1] . '">
                    <a class="btn btn-primary radius" onclick="upload_show_'.$domid.'();">上传图片</a>
                    <style type="text/css">
			.scan{
				padding-top: 5px;
				display: none;
				position: relative;
			}
			.scan ul{
				position: absolute;
				width: 100%;
				left: 0;
				bottom: 0;
				overflow: hidden;
			}
			.scan li{
				float: left;
				padding:0 10px;
				background-color:rgba(0,0,0,.5);
				line-height: 30px;
				text-align: center;
			}
			.scan li a{
				color: white;
			}
		</style>
		<div class="scan" id="'.$domid.'">
			<input type="hidden" name="'.$name.'" value="'.$thumb_id.'">
			<img src="'.($pic).'" onerror="this.src='.$error.'" width="200" alt="">
			<ul>
				<li><a onclick="delscan(this)">删除</a></li>
			</ul>
		</div>
		<script>
			$(document).ready(function() {
			    var dom=$("#'.$domid.'");
			        var val=($("input",dom).val());
			        //console.log(val,val.length);
        		if(val!=""||val.length>0){
        			dom.show();
        		}
        	});
			function delscan(obj){
			    $("input",$(obj).parents(".scan")).val("");
				$("img",$(obj).parents(".scan")).attr("src","");
				$(obj).parents(".scan").hide();
			}
		</script>
                </div>
                <script type="text/javascript">
        			function upload_show_'.$domid.'(){
        				var index=layer_show("上传图片","'.$uploadUrl.'",700,400);
        			}
        		  function '.$callback.'(url,id){
        		      var dom=$("#'.$domid.'");
        		    $("input",dom).val(id);
                    dom.show();
				    $("img",dom)[0].src="'.$path.'/../uploads/"+url;
                  }
        		</script>
                ';
}

function admin_tpl_html($label,$name,$value){
    if (! $value) {
        $value = "";
    } elseif (is_array($value)) {
        if (isset($value[$name])) {
            $value = $value[$name];
        }
    } elseif (is_object($value)) {
        if (isset($value->$name)) {
            $value = $value->$name;
        }
    }
    return '<label class="form-label col-xs-4 col-sm-3">
        '.$label.'：</label><div class="formControls col-xs-8 col-sm-9">
			<script id="container" name="'.$name.'" type="text/plain">'.$value.'</script>
    <!-- 配置文件 -->
    <script type="text/javascript" src="'.config('hui').'/lib/ueditor/1.4.3/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="'.config('hui').'/lib/ueditor/1.4.3/ueditor.all.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor("container");
    </script>
		</div>';
}

/**
 * 是否有子项
 * @param unknown $fid
 * @return boolean
 */
function admin_coclass_haschild($fid){
    $list=Coclass::get([
        'fid'=>$fid
    ]);
    return $list?true:false;
}

/**
 *
 * @param unknown $type
 * @return string
 */
function getFieldType($type){
    switch ($type){
        case "radio":
            $type="varchar(255)";
            break;
        case "checkbox":
            $type="varchar(255)";
            break;
        case "select":
            $type="varchar(255)";
            break;
        case "html":
            $type="text";
            break;
        case "varchar":
            $type.="(255)";
            break;
    };
    return $type;
}
/**
 * 
 * @param unknown $level
 * @return string
 */
function admin_tpl_level($level){
    $s="";
    for ($i=0;$i<$level;$i++){
        $s.="----";
    }
    return $s;
}

function admin_get_data_name($name,$data){
    $res="";
    if(is_array($data)&&isset($data[$name])){
        $res=$value[$name];
    }elseif(is_object($data)&&isset($data->$name)){
        $res=$data->$name;
    }elseif (is_string($data)){
        $res=$data;
    }
    return $res;
}