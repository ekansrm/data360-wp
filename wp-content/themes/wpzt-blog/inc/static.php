<?php
use wpzt\Cache;
function reg_static(){
	
	$jspath=WPZT_DIR.'/static/js';
	if(empty(Cache::get('alljsfile'))){
		$jsfile=get_all_file($jspath);
		Cache::set('alljsfile',$jsfile);
	}else{
		$jsfile=Cache::get('alljsfile');
	}
	
	foreach($jsfile as $v){
		if(strpos($v,'.js')!=0){
			$filename = str_replace($jspath.'/','',$v);
			$filename = str_replace(strrchr($filename, '.'), '', $filename);
			wp_register_script($filename,WPZT_JS.str_replace($jspath,'',$v));
		}
	}
    wp_register_script('jq21','https://s2.pstatp.com/cdn/expire-1-M/jquery/2.1.1/jquery.min.js');
	wp_register_script("bootstrap","https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js");
	wp_register_script('wangeditor','https://s1.pstatp.com/cdn/expire-1-M/wangEditor/3.1.1/wangEditor.min.js');
	wp_register_script('plyr',"https://s0.pstatp.com/cdn/expire-1-M/plyr/3.5.4/plyr.min.js");
    //wp_register_script('sweetalert','https://s3.pstatp.com/cdn/expire-1-M/sweetalert/2.1.0/sweetalert.min.js');
	$csspath=WPZT_DIR.'/static/css';
	
	if(empty(Cache::get('allcssfile'))){
		$cssfile=get_all_file($csspath);
		Cache::set('allcssfile',$cssfile);
	}else{
		$cssfile=Cache::get('allcssfile');
	}
	foreach($cssfile as $v){
		$filename = str_replace($csspath.'/','',$v);
		$filename = str_replace(strrchr($filename, '.'), '', $filename);
		wp_register_style($filename,WPZT_CSS.str_replace($csspath,'',$v));	
	}
	add_css('iconfont');
	add_css('swiper');
	wp_enqueue_style("bootstrap","https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css");
	//wp_enqueue_style("plyr","https://s3.pstatp.com/cdn/expire-1-M/plyr/3.5.4/plyr.css");
	add_css('style');
	
}
function get_all_file($path){
	$filelist=scandir($path);
	$file=[];
	foreach($filelist as $v){
		if($v=="."||$v==".."){
			continue;
		}
		if(is_dir($path.'/'.$v)){
			$file=array_merge($file,get_all_file($path.'/'.$v));
		}else{
			array_push($file,$path.'/'.$v);		
		}
	}
	return $file;
}
add_action( 'wp_enqueue_scripts', 'reg_static' );

function add_js($handle){
	wp_enqueue_script($handle);
}
function add_css($handle){
	 wp_enqueue_style($handle);
}