<?php
namespace wpzt\form;

class Request{
	static function get($param){
		if(empty($_GET[$param])){
			return null;
		}
		$get=esc_sql($_GET[$param]);
		return $get;
	}
	static function post($param){
		if(empty($_POST[$param])){
			return null;
		}
		if(is_array($_POST[$param])){
			return $_POST[$param];
		}
		$post=esc_sql(trim($_POST[$param]));
		return $post;
	}
	static function session($param){
		if(empty($_SESSION[$param])){
			return null;
		}
		return $_SESSION[$param];
	}
	static function url(){
				if(is_ssl()){
					$scheme="https";
				}else{
					$scheme="http";
				}
				return $scheme.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	static function files($param){
		if(empty($_FILES[$param])){
			return null;
		}
		return $_FILES[$param];
	}
	static function setSession($param,$value){
		$_SESSION[$param]=$value;
	}
	static function cookie($param){
		if(empty($_COOKIE[$param])){
			return null;
		}
		return $_COOKIE[$param];
	}
	static function isGet(){
		return $_SERVER['REQUEST_METHOD']=='GET';
	}
	static function isPost(){
		return $_SERVER['REQUEST_METHOD']=='POST';
	}
	static function get_client_ip(){	//客户端IP
		$ip = $_SERVER['REMOTE_ADDR'];
		if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
			foreach ($matches[0] as $xip) {
				if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
					$ip = $xip;
					break;
				}
			}
		}
		return $ip;
	}
	
	static function move($file,$allow=['jpg','jpeg','png'],$allowsize=500*1024){//上传文件
		if(!$file){
			return ['code'=>0,'msg'=>'获取上传文件失败'];
		}
		//$allow=['jpg','jpeg','png'];
		$ext=array_pop(explode('.',$file['name']));
		if(!in_array($ext,$allow)){
			return ['code'=>0,'msg'=>'上传文件格式必须为'.implode(',',$allow)];
		}
		//$allowsize=500*1024;
		$allowsize=$allowsize/1024;
		if($file['size']>$allowsize){
			return ['code'=>0,'msg'=>"文件太大,超过了{$allowsize}K"];
		}
		$outfilepath=wp_upload_dir()['url'].'/'.date('y-m-d',time()).'/';
		$filepath=wp_upload_dir()['path'].'/'.date('y-m-d',time()).'/';
		if(!is_dir($filepath)){
			mkdir($filepath,0766,true);
		}
		$newfilename=date('H-i-S',time()).md5($file['name']).'.'.array_pop(explode('.', $file['name']));
		$filefullpath=$filepath.$newfilename;
		$outfilefullpath=$outfilepath.$newfilename;
		$flag=move_uploaded_file($file['tmp_name'],$filefullpath);
		if($flag){
			return ['code'=>1,'url'=>$outfilefullpath];
		}else{
			return ['code'=>0,'msg'=>'上传出错请重试'];
		}
	}
	
	function input($param){
		if (strpos($param, '/')) {
				list($param, $type) = explode('/', $param, 2);
			} else{
				$type = 's';
			}
		if (strpos($param, '.')) {
				list($method, $param) = explode('.', $param, 2);
			} else {
				$method = 'param';
			}
		 switch (strtolower($method)) {
			 case 'get':$data=esc_sql($_GET[$param]);break;
			 case 'post':$data=esc_sql($_POST[$param]);break;
			 case 'param':
			 switch ($_SERVER['REQUEST_METHOD']) {
				 case 'get':$data=esc_sql($_GET[$param]);break;
				 case 'post':$data=esc_sql($_POST[$param]);break;
				 default:$data=esc_sql($_GET[$param]);
			 }break;
		 }
		  if (!empty($type)) {
            switch (strtolower($type)) {
                case 'a': // 数组
                    $data = (array) $data;
                    break;
                case 'd': // 数字
                    $data = (int) $data;
                    break;
                case 'f': // 浮点
                    $data = (float) $data;
                    break;
                case 'b': // 布尔
                    $data = (boolean) $data;
                    break;
                case 's': // 字符串
                default:
                    $data = (string) $data;
            }
        }
		return $data;
	}
	
	function getServerName(){
		return $_SERVER[‘SERVER_NAME’];
	}
}