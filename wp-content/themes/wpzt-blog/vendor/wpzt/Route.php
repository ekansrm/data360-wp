<?php
namespace wpzt;
use wpzt\Cache;

class Route{	//路由类
	private $path;//页面目录
	private $filelist;
	function __construct($path=WPZT_DIR.DS.'home'.DS){
		$this->path=$this->doneds($path);
		$this->filelist=$this->getkeyfile();
		add_action('init', array($this,'addrouter'));
		add_action('query_vars', array($this,'wpzt_add_query_vars'));//获取链接参数
		add_action("template_include", array($this,'wpzt_template_include'));//判断页面跳转
	}
	
	 function addrouter(){
		if(empty($this->filelist)){
			return;
		}
		foreach($this->filelist as $k=>$v){
			add_rewrite_rule('^'.$k.'/?$','index.php?act='.$k,'top');
		
		}
		//以下添加分页
		add_rewrite_rule('^mypostlist/page/([0-9]+)/?$','index.php?act=mypostlist&paged=$matches[1]','top');
		
		//add_rewrite_rule('^getvip/page/([0-9]+)/?$','index.php?act=getvip&paged=$matches[1]','top');
		
	}
	
	 function wpzt_add_query_vars($public_query_vars){
		$public_query_vars[] = 'act'; 
		$public_query_vars[]='mypaged';
		return $public_query_vars;
	}
	
	function wpzt_template_include($original_template){
		global $wp;
		global $wp_query;
		if(empty($wp_query->query_vars['act'])){
			return $original_template;
		}
		$redirect_page =  $wp_query->query_vars['act'];
		if(array_key_exists($redirect_page,$this->filelist)){
			return $this->filelist[$redirect_page];
		}else{
			return $original_template;
		}
	}
	private function getkeyfile(){//返回文件名，文件路径数组
		$cachekey='routerfile'.md5($this->path);
		if(Cache::has($cachekey)){
			return Cache::get($cachekey);
		}
		$allfile=$this->get_all_file($this->path);
		$routerfile=$this->filetoarray($allfile);
		Cache::set($cachekey,$routerfile);
		return $routerfile;
	}
	private function get_all_file($path){	//获取全部文件
		
		$filelist=scandir($path);
		$file=[];
		foreach($filelist as $v){
			if($v=="."||$v==".."){
				continue;
			}
			if(is_dir($path.DS.$v)){
				$file=array_merge($file,$this->get_all_file($path.$v.DS));
			}else{
				array_push($file,$path.$v);		
			}
		}	
		return $file;
	}
	
	private function filetoarray($filelist){//文件转数组
		$file=[];
		if(!is_array($filelist)||empty($filelist)){
			return $file;
		}
		foreach($filelist as $v){
			$filekey=str_replace($this->path,'',$v);
			$filekey=str_replace(['.php','\\','/'],['','_','_'],$filekey);
			$file[$filekey]=$v;
		}
		return $file;
	}
	private function doneds($path){
		return str_replace(['/','\\'],DS,$path);
	}
}