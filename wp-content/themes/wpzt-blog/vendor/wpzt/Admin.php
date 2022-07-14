<?php
namespace wpzt;

class Admin{
	
	function __construct(){
		//自定义后台版权信息
		add_filter('admin_footer_text', array($this,'banquan'));
		//去掉后台Wordpress LOGO
		add_action('admin_bar_menu', array($this,'my_edit_toolbar'), 999);
		//后台菜单部分
		add_action('admin_menu',array($this,'admin_menu'));
		
		//WordPress替换登陆后跳转的后台默认首页
		// if( wpzt('wpzt_article')){
			// add_filter("login_redirect", array($this,"my_login_redirect"), 10, 3);
		//}
		
		//彻底删除后台隐私相关设置
		if( wpzt('wpzt_privacy') ){
			add_action('admin_menu', array($this,'delprivacy'),9);
		}
		
		//禁用日志修订功能
		if( wpzt('wpzt_revision') ){
			add_action('wp_print_scripts',function() {wp_deregister_script('autosave');});
			//define('WP_POST_REVISIONS', false);
			add_filter("wp_revisions_to_keep",function(){return 0;});
			remove_action('pre_post_update', 'wp_save_post_revision' );
			// 自动保存设置为10个小时
			//define('AUTOSAVE_INTERVAL', 36000 );
		}
		//删除文章时删除图片附件
		if( wpzt('wpzt_delete_post_attachments') ){
			add_action('before_delete_post', array($this,'wpzt_delete_post_and_attachments'));
		}
		//删除后台外观 编辑选项
		add_action('admin_init',array($this,'remove_submenu')); 
		add_action( 'admin_head', array($this,'custom_menu_css') );//后台页头添加CSS
		//移除后台标题后缀 - WordPress
		add_filter('admin_title',array($this,'wpzt_custom_admin_title'), 10, 2);
		//上传图片日期重命名
		if( wpzt('wpzt_upload_img_rename') ){
			add_filter('wp_handle_upload_prefilter', array($this,'wpzt_wp_upload_filter')); 
		}
		//禁用古腾堡编辑器
		if( wpzt('wpzt_no_gutenberg') ) {
			add_filter('use_block_editor_for_post_type', '__return_false');
		}
		if(!empty(wpzt('bdzy_url'))){	//百度资源提交
			add_action('publish_post', array($this,'Baiduziyuan_Submit'), 0);
		}
		add_filter( 'manage_posts_columns', array($this,'wpzt_post_id_column'));//添加文章列表页ID标题
	
		add_action( 'manage_posts_custom_column', array($this,'wpzt_posts_id_column'), 10, 2 );//添加文章列表页ID列数值
		
		add_action( 'admin_head-edit.php', array($this,'wpzt_posts_id_column_css') );//ID宽度
		register_nav_menus(['main'	=> '主菜单','side'=>'左侧菜单']);
		
		add_theme_support('post-thumbnails');
		add_theme_support('post-formats',array( 'image','gallery','video' ) );
		add_filter( 'esc_html', array($this,'rename_post_formats'));
		add_filter( 'pre_option_link_manager_enabled', '__return_true' );
		//自动替换媒体库图片的域名
		if (wpzt('cdn_url') && wpzt('admin_cdn')&&wpzt('open_cdn')) {
			 add_filter('wp_get_attachment_url', array($this,'wpzt_attachment_replace'));
		}
		
		if(wpzt('wpzt_wp_update')){
			//取消自动更新
			add_filter( 'automatic_updater_disabled', '__return_true' );
			add_filter('pre_site_transient_update_core',    function(){return null;}); // 关闭核心提示 
			remove_action('admin_init', '_maybe_update_core');    // 禁止 WordPress 检查更新
			// 移除后台插件更新检查 
			remove_action( 'load-plugins.php', 'wp_update_plugins' );
			remove_action( 'load-update.php', 'wp_update_plugins' );
			remove_action( 'load-update-core.php', 'wp_update_plugins' );
			remove_action( 'admin_init', '_maybe_update_plugins' );
			 // 移除后台主题更新检查 
			remove_action( 'load-themes.php', 'wp_update_themes' );
			remove_action( 'load-update.php', 'wp_update_themes' );
			remove_action( 'load-update-core.php', 'wp_update_themes' );
			remove_action( 'admin_init', '_maybe_update_themes' );
		}	
		//仪表盘
		add_action('wp_dashboard_setup', array($this,'disable_dashboard_widgets'), 999);
		
		//禁止加载google字体
		add_action('init', array($this,'wpzt_remove_open_sans_from_wp_core'));
		
		if(!current_user_can( 'manage_options' )){
			add_action( 'admin_menu', array($this,'remove_user_menu'), 20 );
			add_action('load-profile.php',array($this,'redirect_to_usercenter'));
			add_filter('parse_query', array($this,'wpzt_parse_query_useronly'));
		}
	
		
		if(get_option('upload_path')=='wp-content/uploads' || get_option('upload_path')==null) {
			update_option('upload_path',WP_CONTENT_DIR.'/uploads');
		}
		
		
		add_action('do_meta_boxes',array($this,'remove_post_meta_box'));
	}
	
	
	function remove_user_menu(){
		global $menu;
		remove_menu_page('tools.php');
		remove_menu_page('index.php');
		//remove_menu_page('profile.php');
		remove_menu_page('edit-comments.php');
		$menu[70][0]='用户中心';
	}
	
	function redirect_to_usercenter(){
		wp_redirect(home_url('usercenter'));exit;
	}
	
	
	function wpzt_parse_query_useronly($wp_query){
		if(strpos($_SERVER['REQUEST_URI'],'/wp-admin/edit.php')!==false){
			$uid=get_current_user_id();
			$wp_query->set('author',$uid);
		}
	}
	
	function banquan(){
		echo '<span id="footer-thankyou">感谢使用 <a href="http://www.liuyiidc.com" target="_blank" style="text-transform: uppercase;">'.wp_get_theme().'主题</a> 进行创作</span>';
	}
	
	
	function my_edit_toolbar($wp_toolbar) {	//去除logo
		$wp_toolbar->remove_node('wp-logo'); 
	}
	
	
	
	function admin_menu(){	//后台菜单
		//后台菜单重命名
		global $menu;
		
		$menu[80][0] = '设置';
		//屏蔽设置下的媒体菜单
		if( wpzt('wpzt_option_thumbnail') ) :
			remove_submenu_page('options-general.php', 'options-media.php');
		endif;
		//开启所有设置
		if( wpzt('wpzt_all_settings') ) :
			add_options_page('高级设置', '高级设置', 'administrator', 'options.php'); 
		endif;
	}

		
	function my_login_redirect($redirect_to, $request,$user){//后台登录重定向到添加文档
			if( empty( $redirect_to ) || $redirect_to == home_url('wp-admin') || $redirect_to == admin_url() ){
				return home_url("/wp-admin/edit.php");
			}else{
				return $redirect_to;
			}
	}
			
	function delprivacy (){	//彻底删除后台隐私相关设置
		global $menu, $submenu;
		unset($submenu['options-general.php'][45]);
		// Bookmark hooks.
		remove_action( 'admin_page_access_denied', 'wp_link_manager_disabled_message' );
		// Privacy tools
		remove_action( 'admin_menu', '_wp_privacy_hook_requests_page' );
		// Privacy hooks
		remove_filter( 'wp_privacy_personal_data_erasure_page', 'wp_privacy_process_personal_data_erasure_page', 10, 5 );
		remove_filter( 'wp_privacy_personal_data_export_page', 'wp_privacy_process_personal_data_export_page', 10, 7 );
		remove_filter( 'wp_privacy_personal_data_export_file', 'wp_privacy_generate_personal_data_export_file', 10 );
		remove_filter( 'wp_privacy_personal_data_erased', '_wp_privacy_send_erasure_fulfillment_notification', 10 );

		// Privacy policy text changes check.
		remove_action( 'admin_init', array( 'WP_Privacy_Policy_Content', 'text_change_check' ), 100 );

		// Show a "postbox" with the text suggestions for a privacy policy.
		remove_action( 'edit_form_after_title', array( 'WP_Privacy_Policy_Content', 'notice' ) );

		// Add the suggested policy text from WordPress.
		remove_action( 'admin_init', array( 'WP_Privacy_Policy_Content', 'add_suggested_content' ), 1 );

		// Update the cached policy info when the policy page is updated.
		remove_action( 'post_updated', array( 'WP_Privacy_Policy_Content', '_policy_page_updated' ) );
	}
	
	function wpzt_delete_post_and_attachments($post_ID) {//删除文章时删除图片附件
		global $wpdb;
		//删除特色图片
		$thumbnails = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id' AND post_id = $post_ID" );
		foreach ( $thumbnails as $thumbnail ) {
		wp_delete_attachment( $thumbnail->meta_value, true );
		}
		//删除图片附件
		$attachments = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_parent = $post_ID AND post_type = 'attachment'" );
		foreach ( $attachments as $attachment ) {
		wp_delete_attachment( $attachment->ID, true );
		}
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id' AND post_id = $post_ID" );
	}
		
	function remove_submenu() {   
		// 删除"外观"下面的子菜单"编辑"   
		remove_submenu_page( 'themes.php', 'theme-editor.php' );   
	}

	
	function custom_menu_css() {  
		$custom_menu_css = '<style type="text/css">  
			#wp-admin-bar-custom_menu img { margin:0 0 -4px 0; } /** moves icon over */  
			#wp-admin-bar-custom_menu { width:75px; } /** sets width of custom menu */  
			#wp-admin-bar-custom_menu {width:92px;}
			.wp-first-item.wp-not-current-submenu.wp-menu-separator,.hide-if-no-customize{display: none;}
		</style>';  
		echo $custom_menu_css;  
	}  
	
	
	function wpzt_custom_admin_title($admin_title, $title){
		return $title.' &lsaquo; '.get_bloginfo('name');
	}
		
	//上传图片使用日期重命名	
	function wpzt_wp_upload_filter($file){
			if(strpos(strtolower($file['name']),'.ico')!=false){
				return $file;
			}
			$time=date("YmdHis");  
			$file['name'] = $time."".mt_rand(1,100).".".pathinfo($file['name'] , PATHINFO_EXTENSION);  
			return $file;  
	}  
		
	function Baiduziyuan_Submit($post_ID) {
        if(get_post_meta($post_ID,'Baiduziyuansubmit',true) == 1) return;
        $url = get_permalink($post_ID);
        $api = wpzt('bdzy_url');
        $request = new \WP_Http;
        $result = $request->request( $api , array( 'method' => 'POST', 'body' => $url , 'headers' => 'Content-Type: text/plain') );
       
        if(is_wp_error($result)){
			return;
		}
		 $result = json_decode($result['body'],true);
        if (array_key_exists('success',$result)) {
            add_post_meta($post_ID, 'Baiduziyuansubmit', 1, true);
        }
    }
		
	
	//文章列表添加ID
	function wpzt_post_id_column( $post_columns ) {
		$beginning = array_slice( $post_columns, 0 ,1 );
		$beginning[ 'postid' ] = 'ID';
		$ending = array_slice( $post_columns, 1 );
		$post_columns = array_merge( $beginning, $ending );
	
		if(!empty(wpzt('bdzy_url'))){
			$post_columns['baiduziyuan']='百度资源';
		}
		//unset($post_columns['taxonomy-special']);
		return $post_columns;
	}
		 
	function wpzt_posts_id_column( $col, $post_id ) {
		if( $col == 'postid' ) echo $post_id;
			if(!empty(wpzt('bdzy_url'))){
				if($col=='baiduziyuan'){
					$baidu=get_post_meta($post_id,'Baiduziyuansubmit',true);
					$baiducode=empty($baidu)?'未提交':'已提交';
					echo $baiducode;
				}
			}		
		$meta=wpzt_post($post_id);
	}
	 
	function wpzt_posts_id_column_css() {
		echo '<style type="text/css">#postid,#price,#vipprice,#views { width: 50px; }</style>';//ID列宽度
		
	}
	
	
	function rename_post_formats( $safe_text ) {
	if ( $safe_text == '图像' )
		return '大图';
		return $safe_text;
	}
	
	function wpzt_attachment_replace($text) {
        $replace = array(
            '' . home_url() . '' => '' . wpzt('cdn_url') . ''
        );
        $text = str_replace(array_keys($replace) , $replace, $text);
        return $text;
    }
	
	//删除 WordPress 后台仪表盘
	function disable_dashboard_widgets() {
		global $wp_meta_boxes;
		//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	}
	
	
	
	
	
	
	
	
	//禁止加载google字体
	function wpzt_remove_open_sans_from_wp_core(){
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', false);
		wp_enqueue_style('open-sans','');
	}
	
	
	
	function remove_post_meta_box(){
		remove_meta_box('formatdiv', 'post', 'side');
	}
	
}
