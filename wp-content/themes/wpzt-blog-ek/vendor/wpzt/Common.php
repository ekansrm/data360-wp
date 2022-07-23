<?php
namespace wpzt;

class Common{
	function __construct(){
		//去除加载的css和js后面的版本号
		if( wpzt('wpzt_remove_script_version') ){
			add_filter( 'script_loader_src', array($this,'_remove_script_version'), 15, 1 );
			add_filter( 'style_loader_src', array($this,'_remove_script_version'), 15, 1 );
			add_filter( 'pre_option_link_manager_enabled', '__return_true' );
		}		
		 // WordPress 关闭 XML-RPC 的 pingback 端口
		if( wpzt('wpzt_pingback') ) {
			add_filter( 'xmlrpc_methods', array($this,'remove_xmlrpc_pingback_ping' ));
		}
		//禁止头部加载s.w.org
		add_filter( 'wp_resource_hints',array($this,'unloadsworg'),10,2);
		//移除头部emoji.js加载
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		//使用自定义头像
		if( wpzt('wpzt_v2ex') ) {
			add_filter( 'get_avatar',array($this,'use_v2ex_avatar'),10,3);
		}
		//禁止生成多尺寸图片
		if( wpzt('wpzt_option_thumbnail') ){
			add_filter('pre_option_thumbnail_size_w',	'__return_zero');
			add_filter('pre_option_thumbnail_size_h',	'__return_zero');
			add_filter('pre_option_medium_size_w',		'__return_zero');
			add_filter('pre_option_medium_size_h',		'__return_zero');
			add_filter('pre_option_large_size_w',		'__return_zero');
			add_filter('pre_option_large_size_h',		'__return_zero');
		}
		//移除顶部多余信息
		if( wpzt('wpzt_wp_head') ) {
			remove_action( 'wp_head', 'feed_links', 2 ); //移除feed
			remove_action( 'wp_head', 'feed_links_extra', 3 ); //移除feed
			remove_action( 'wp_head', 'rsd_link' ); //移除离线编辑器开放接口
			remove_action( 'wp_head', 'wlwmanifest_link' );  //移除离线编辑器开放接口
			remove_action( 'wp_head', 'index_rel_link' );//去除本页唯一链接信息
			remove_action('wp_head', 'parent_post_rel_link', 10, 0 );//清除前后文信息
			remove_action('wp_head', 'start_post_rel_link', 10, 0 );//清除前后文信息
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
			remove_action( 'wp_head', 'locale_stylesheet' );
			//remove_action('publish_future_post','check_and_publish_future_post',10, 1 );//定时发布
			remove_action( 'wp_head', 'noindex', 1 );
			remove_action( 'wp_head', 'wp_generator' ); //移除WordPress版本
			remove_action( 'wp_head', 'rel_canonical' );
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
			remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
		}
	
		
		//去除分类标志代码
		if(wpzt('wpzt_category')){
			add_action( 'load-themes.php',  array($this,'no_category_base_refresh_rules'));
			add_action('created_category', array($this,'no_category_base_refresh_rules'));
			add_action('edited_category', array($this,'no_category_base_refresh_rules'));
			add_action('delete_category', array($this,'no_category_base_refresh_rules'));
			add_action('init', array($this,'no_category_base_permastruct'));
			// Add our custom category rewrite rules
			add_filter('category_rewrite_rules', array($this,'no_category_base_rewrite_rules'));
			// Add 'category_redirect' query variable
			add_filter('query_vars', array($this,'no_category_base_query_vars'));
			// Redirect if 'category_redirect' is set
			add_filter('request', array($this,'no_category_base_request'));
		}
		
		add_action('init', array($this,'wp_do_output_buffer'));//清除缓存，页面跳转需要
	//	add_action( 'init', array($this,'create_special_taxonomies'));
		add_action('init',array($this,'open_session'));
		add_filter('login_url',array($this,'change_login_url'),10,3);//更改登录链接
		//添加专题
		add_action( 'init', array($this,'create_special_taxonomies'));
		
		//媒体库和文章权限
		add_action('pre_get_posts',array($this,'wpzt_upload_media'));//在文章编辑页面的[添加媒体]只显示用户自己上传的文件
		add_filter('parse_query', array($this,'wpzt_media_library'));//在[媒体库]只显示用户上传的文件
		//编辑器添加下一页
		add_filter('mce_buttons',array($this,'wp_add_next_page_button'));
		if(wpzt('cache-save-post')){
			add_action('publish_post', array($this,'publish_post_clear_cache'), 0);
		}
		
		//作者归档页链接重写
		add_filter( 'author_link', array($this,'wpzt_author_link'), 10, 2 );
		add_filter( 'request', array($this,'wpzt_author_link_request' ));
	} 
	
	function _remove_script_version( $src ){
		$parts = explode( '?', $src );
		return $parts[0];
	}
	function remove_xmlrpc_pingback_ping( $methods ) {
		unset( $methods['pingback.ping'] );
		return $methods;
	}
	function unloadsworg($hints, $relation_type ) {
		if ( 'dns-prefetch' === $relation_type ) {
			return array_diff( wp_dependencies_unique_hosts(), $hints );
		}
		return $hints;
	}
	function use_v2ex_avatar($avatar,$user) {
		if(is_numeric($user)){
			$avatar_img=get_user_avatar($user);
		}else{
			$avatar_img=get_user_avatar($user->user_id);
		}
		return "<img  src='{$avatar_img}'  class='avatar avatar-50 photo' height='50' width='50'>";
	}
	//去除category
	function no_category_base_refresh_rules() {
		global $wp_rewrite;
		$wp_rewrite -> flush_rules();
	}
	function no_category_base_permastruct() {
		global $wp_rewrite, $wp_version;
		if (version_compare($wp_version, '3.4', '<')) {
			// For pre-3.4 support
			$wp_rewrite -> extra_permastructs['category'][0] = '%category%';
		} else {
			$wp_rewrite -> extra_permastructs['category']['struct'] = '%category%';
		}
	}
	function no_category_base_rewrite_rules($category_rewrite) {
		$category_rewrite = array();
		$categories = get_categories(array('hide_empty' => false));
		foreach ($categories as $category) {
			$category_nicename = $category -> slug;
			if ($category -> parent == $category -> cat_ID)// recursive recursion
				$category -> parent = 0;
			elseif ($category -> parent != 0)
				$category_nicename = get_category_parents($category -> parent, false, '/', true) . $category_nicename;
			$category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
			$category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
			$category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
		}
		// Redirect support from Old Category Base
		global $wp_rewrite;
		$old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
		$old_category_base = trim($old_category_base, '/');
		$category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';
		return $category_rewrite;
	}
	function no_category_base_query_vars($public_query_vars) {
		$public_query_vars[] = 'category_redirect';
		return $public_query_vars;
	}
	function no_category_base_request($query_vars) {
		if (isset($query_vars['category_redirect'])) {
			$catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
			status_header(301);
			header("Location: $catlink");
			exit();
		}
		return $query_vars;
	}
	

	function wp_do_output_buffer() {	//!!!!重要wp_redirect()出错问题解决
	 if( begin_page_cache()){
		        exit;
		}
	}
	
	
	
	function open_session(){
		if(session_status() !== PHP_SESSION_ACTIVE){
			session_start();
		}
	}

	function change_login_url($login_url,$redirect, $force_reauth){	//改变函数wp_login_url的跳转地址
		if(empty(strstr($login_url,'wp-admin'))){
			$login_url=home_url('login');
		}else{
			return $login_url;
		}
		 if ( ! empty( $redirect ) ) {
			$login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
		}else{
			$redirect=\wpzt\form\Request::url();
			
			$login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
		}
 
		if ( $force_reauth ) {
			$login_url = add_query_arg( 'reauth', '1', $login_url );
		}
		return $login_url;
	}

	function wpzt_upload_media( $wp_query_obj ) {
		global $current_user, $pagenow;
		if( !is_a( $current_user, 'WP_User') )
			return;
		if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
			return;
		if( !current_user_can( 'manage_options' ) && !current_user_can('manage_media_library') )
			$wp_query_obj->set('author', $current_user->ID );
		return;
	}

	function wpzt_media_library( $wp_query ) {
		if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
			if ( !current_user_can( 'manage_options' ) && !current_user_can( 'manage_media_library' ) ) {
				global $current_user;
				$wp_query->set( 'author', $current_user->id );
			}
		}
	}
	
	function wp_add_next_page_button($mce_buttons) {//内容分页
		$pos = array_search('wp_more',$mce_buttons,true);  
		if ($pos !== false){
			$tmp_buttons = array_slice($mce_buttons, 0, $pos+1);  
			$tmp_buttons[] = 'wp_page';  
			$mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));  
		}
		return $mce_buttons;  
	}

		function publish_post_clear_cache($postid){
			admin_clear_cache();
			\wpzt\Cache::clear(null);
		}
		
	function create_special_taxonomies() {
		 // Add new taxonomy, make it hierarchical (like categories)
		 $labels = array(
		 'name'              => _x( '专题', 'taxonomy general name', 'textdomain' ),
		 'singular_name'     => _x( '专题分类', 'taxonomy singular name', 'textdomain' ),
		 'search_items'      => __( '查询专题', 'textdomain' ),
		 'all_items'         => __( '全部专题', 'textdomain' ),
		 'parent_item'       => __( '上级专题', 'textdomain' ),
		 'parent_item_colon' => __( '上级专题', 'textdomain' ),
		 'edit_item'         => __( '编辑专题', 'textdomain' ),
		 'update_item'       => __( '更改专题', 'textdomain' ),
		 'add_new_item'      => __( '添加新专题', 'textdomain' ),
		 'new_item_name'     => __( '新专题名字', 'textdomain' ),
		 'menu_name'         => __( '专题', 'textdomain' ),
		 );
		 
		 $args = array(
		 'hierarchical'      => true,
		 'labels'            => $labels,
		 'show_ui'           => true,
		 'show_in_menu'      => true,
		 'show_in_nav_menus' => true,
		 'show_admin_column' => true,
		 'query_var'         => true,
		 'rewrite'           => array( 'slug' => 'special' ),
		 );
		 
		 register_taxonomy( 'special', array( 'post' ), $args );
	}

	function wpzt_author_link( $link,$author_id) {
		global $wp_rewrite;
		$author_id = (int) $author_id;
		$link = $wp_rewrite->get_author_permastruct();
		
		if ( empty($link) ) {
			$file = home_url( '/' );
			$link = $file . '?author=' . $author_id;
		} else {
			$link = str_replace('%author%', $author_id, $link);
			$link = home_url( user_trailingslashit( $link ) );
		}
	 
		return $link;
	}
	
	function wpzt_author_link_request($query_vars ) {
		if ( array_key_exists( 'author_name', $query_vars ) ) {
			global $wpdb;
			$author_id=$query_vars['author_name'];
			if ( $author_id ) {
				$query_vars['author'] = $author_id;
				unset( $query_vars['author_name'] );    
			}
		}
		return $query_vars;
	}
}