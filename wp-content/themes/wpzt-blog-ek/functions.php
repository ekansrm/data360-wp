<?php
error_reporting(E_ALL^E_NOTICE);

date_default_timezone_set('Asia/Shanghai');
define('DS',DIRECTORY_SEPARATOR);
define('WPZT_URL',get_template_directory_uri());
define('WPZT_JS',WPZT_URL.'/static/js');
define('WPZT_CSS',WPZT_URL.'/static/css');
define('WPZT_IMG',WPZT_URL.'/static/images');
define('WPZT_DIR',get_template_directory());
//define('WPZT_CACHE',WP_CONTENT_DIR.'/cache');
define('WPZT_CACHE',str_replace('/',DS,WP_CONTENT_DIR.'/cache/'));
define('WPZT_VERSION',1.1);	//程序版本号

include_once WPZT_DIR.'/vendor/autoload.php';

if( wpzt('wpzt_wp-clean-up') ){
	include_once WPZT_DIR.'/admin/wp-clean-up/wp-clean-up.php';
}
if( wpzt('wpzt_sitemap') ) {
	include_once WPZT_DIR.'/admin/Sitemap/sitemap.php';
}
if(is_admin()){
	$admin=new wpzt\Admin();
	$page=new wpzt\Page();
	$adminpage=new wpzt\Adminpage();
}
if(!is_admin()){
	$home=new wpzt\Home();
	
}
$com=new wpzt\Common();
$ajax=new wpzt\Ajax();
$route=new wpzt\Route();


include_once WPZT_DIR.'/inc/static.php';

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );
/*
function initdatabase(){
	global $pagenow;
	if ('themes.php' == $pagenow && isset( $_GET['activated'])){
		$wpzt_zy_theme_version=get_option('wpzt_video_theme_version');
		if(empty($wpzt_zy_theme_version)){
			require_once(WPZT_DIR.'/inc/init.php');
		}
	}
}*/
//add_action( 'load-themes.php','initdatabase');//数据库初始化

 //include_once WPZT_DIR.'/inc/debug.php';
// letan_option_url();
