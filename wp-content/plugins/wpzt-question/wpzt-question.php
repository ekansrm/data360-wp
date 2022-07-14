<?php 
/*
Plugin Name:WPZT-Question
Plugin URI: https://www.wpzt.net
Description: 为主题盒子的资讯类主题提供一款问答插件，让资讯主题轻松变为问答主题(注意非主题盒子的主题不能使用)。
Version: 1.2
Author: 主题盒子
Author URI: https://www.wpzt.net
Text Domain: wpzt
*/
define('WPZT_PQ_URL', plugin_dir_url(__FILE__));
define('WPZT_PQ_DIR',__DIR__ . DIRECTORY_SEPARATOR);
define('WPZT_PQ_JS',WPZT_PQ_URL.'/static/js');
define('WPZT_PQ_CSS',WPZT_PQ_URL.'/static/css');
define('WPZT_PQ_IMG',WPZT_PQ_URL.'/static/images');
define('WPZT_PQ_QUESTION','_question');
define('WPZT_PQ_OPTION','_question_option');
define('WPZT_PQ_QUESTION_TAX','_question_tax');
define('WPZT_PQ_QUESTION_WID','_question_wid');
require_once WPZT_PQ_DIR.'/inc/utils.php';
class wpzt_question{
	function __construct(){
		add_action( 'init', array($this,'post_type_question') );//问答类型文档
		add_action( 'init', array($this,'create_question_taxonomies'), 0 );//问答分类
		add_filter('post_type_link', array($this,'custom_question_link'), 1, 3);	//问答链接自定义
		add_action( 'init', array($this,'question_rewrites_init') );//问答链接重写
		add_action('query_vars',array($this,'add_wpzt_question_vars'));
		add_action('csf_init',array($this,'add_admin_fields'));
		add_filter('template_include',array($this,'get_pq_template'));
		add_action('wp_ajax_upload_wang_image',array($this,'upload_wang_image'));//编辑器图片上传
		add_action( 'wp_head', array($this,'wpzt_seo_meta_action'));	//seo
		add_filter( 'document_title_parts', array($this,'filter_document_title_parts'), 10, 1 );
		add_action( 'wp_enqueue_scripts', array($this,'add_static_file') );
		add_action('wp_ajax_add_question',array($this,'add_question'));
		add_action('wp_ajax_wpzt_add_comment',array($this,'wpzt_add_comment'));
		
		add_action('wp_ajax_checklogin',array($this,'checklogin'));
		add_action('wp_ajax_nopriv_checklogin',array($this,'checklogin'));
		add_action('wp_ajax_get_page_comment',array($this,'get_page_comment'));
		add_action('wp_ajax_nopriv_get_page_comment',array($this,'get_page_comment'));
		$this->add_side_widget();//添加小工具侧边栏
		
		add_filter('get_comments_number',array($this,'get_comments_number'),10,2);//评论相关
		add_filter('pre_get_posts', array($this,'search_filter_page'),100,1);//文档类型添加aq
		
		add_action('wp_ajax_delete_comment',array($this,'delete_comment'));
	}
	
	function post_type_question() {	//添加问答文档
		$labels = array(
			'name'               => '问题',
			'singular_name'      => '问题', 
			'menu_name'          => '问题', 
			'name_admin_bar'     => '问题', 
			'add_new'            => '发布问题', 
			'add_new_item'       => '发布新问题',
			'new_item'           => '新问题', 
			'edit_item'          => '编辑问题', 
			'view_item'          => '查看问题', 
			'all_items'          => '所有问题', 
			'search_items'       => '搜索问题', 
			'parent_item_colon'  => 'Parent 问题:', 
			'not_found'          => '你还没有发布问题。',
			'not_found_in_trash' => '回收站中没有问题。', 
		);
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'aq' ),
			'capability_type'    => 'post',
			'menu_icon'          => 'dashicons-cart',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 10,
			'supports'           => array( 'title', 'editor', 'author',  'comments', )
		);
		register_post_type( 'aq', $args );
	}
	
	function create_question_taxonomies() {	//问答分类
		 $labels = array(
		 'name'              => '问题分类',
		 'singular_name'     => '问题分类', 
		 'search_items'      => '查询分类', 
		 'all_items'         => '全部分类', 
		 'parent_item'       => '上级分类',
		 'parent_item_colon' => '上级分类',
		 'edit_item'         => '编辑分类', 
		 'update_item'       => '更改分类', 
		 'add_new_item'      => '添加新分类', 
		 'new_item_name'     => '新分类名称',
		 'menu_name'         => '问题分类', 
		 );
		 
		 $args = array(
		 'hierarchical'      => true,
		 'labels'            => $labels,
		 'show_ui'           => true,
		 'show_in_menu'      => true,
		 'show_in_nav_menus' => true,
		 'show_admin_column' => true,
		 'query_var'         => true,
		 'rewrite'           => array( 'slug' => 'question' ),
		 );
		 
		 register_taxonomy( 'question', array( 'aq' ), $args );
	}

	function custom_question_link($link,$post){	//问答页链接
		 if ( $post->post_type == 'aq' ){
				return home_url( 'question/' . $post->ID .'.html' );
			} else {
				return $link;
			}
	}
	
	function question_rewrites_init(){	//问答url重写
		add_rewrite_rule('question/([0-9]+)?.html$','index.php?post_type=aq&p=$matches[1]','top');
		add_rewrite_rule('question/([0-9]+)?.html/comment-page-([0-9]{1,})$','index.php?post_type=aq&p=$matches[1]&cpage=$matches[2]','top');
		add_rewrite_rule('^addquestion/?$','index.php?act=addquestion','top');
		add_rewrite_rule('userquestion/([0-9]+)$','index.php?act=userquestion&wpzt_uid=$matches[1]','top');
		add_rewrite_rule('userquestion/([0-9]+)/page/([0-9]+)$','index.php?act=userquestion&wpzt_uid=$matches[1]&paged=$matches[2]','top');
		add_rewrite_rule('useraq/([0-9]+)$','index.php?act=useraq&wpzt_uid=$matches[1]','top');
		add_rewrite_rule('useraq/([0-9]+)/page/([0-9]+)$','index.php?act=useraq&wpzt_uid=$matches[1]&paged=$matches[2]','top');
		add_rewrite_rule('userpost/([0-9]+)$','index.php?act=userpost&wpzt_uid=$matches[1]','top');
		add_rewrite_rule('userpost/([0-9]+)/page/([0-9]+)$','index.php?act=userpost&wpzt_uid=$matches[1]&paged=$matches[2]','top');
	}
	
	function add_wpzt_question_vars($public_query_vars){
		if(!in_array('act',$public_query_vars)){
			$public_query_vars[]='act';
		}
		if(!in_array('wpzt_uid',$public_query_vars)){
			$public_query_vars[]='wpzt_uid';
		}
		
		return $public_query_vars;
	}
	
	function add_admin_fields(){//添加后台字段
		require_once 'inc/csf/taxonomy.php';
		require_once 'inc/csf/metabox.php';
		require_once 'inc/csf/widgets.php';
		require_once 'inc/csf/options.php';
	}
	
	function get_pq_template($template){	//获取模板
		global $wp_query;
		if(!empty($wp_query->query_vars['act'])){	//用户文章问答和回答列表
			if($wp_query->query_vars['act']=='userquestion'){
				return WPZT_PQ_DIR.'/templates/userquestion.php';
			}elseif($wp_query->query_vars['act']=='useraq'){
				return WPZT_PQ_DIR.'/templates/useraq.php';
			}elseif($wp_query->query_vars['act']=='userpost'){
				return WPZT_PQ_DIR.'templates/userpost.php';
			}
		}
		if(is_tax('question')){
			return WPZT_PQ_DIR.'templates/tax.php';
		}elseif(is_single()){
			$object=get_queried_object();
			if($object->post_type=='aq'){
				add_filter('comments_template',function(){
					return WPZT_PQ_DIR.'/templates/comments.php';	//问题模板
				});
				return WPZT_PQ_DIR.'templates/single.php';
			}
			return $template;
		}else{
			return $template;
		}
	}

	function upload_wang_image(){
			if($_FILES){	
			$file=$_FILES['file'];
			$allow=['jpg','jpeg','png','gif'];
			$ext=array_pop(explode('.',$file['name']));
			if(!in_array($ext,$allow)){
				wp_send_json(['code'=>0,'msg'=>'上传文件格式必须为png,jpg,jpeg']);
			}
			$allowsize=500*1024;
			if($file['size']>$allowsize){
				wp_send_json(['code'=>0,'msg'=>'文件要小于500K']);
			}
			
			if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
			$upload_overrides = array( 'test_form' => false );
			$uploadfile=wp_handle_upload($file,$upload_overrides);
			wp_send_json(['errno'=>0,'data'=>[$uploadfile['url']]]);	
		}else{
			wp_send_json(['errno'=>1]);
		}
	}

	function wpzt_seo_meta_action(){	//key description的ＳＥＯ
		if(is_single()){
			$object=get_queried_object();
			if($object->post_type=='aq'){
				$post_extend = get_post_meta( get_the_ID(), '_question', true );
				$key_meta = isset($post_extend['seo_custom_keywords']) ? $post_extend['seo_custom_keywords'] : '';
				$des_meta = isset($post_extend['seo_custom_desc']) ? $post_extend['seo_custom_desc'] : '';
				if(!empty($des_meta)){
					echo '<meta name="description" content="'.esc_attr($des_meta).'" />';
					echo "\n";
				}
				if(!empty($key_meta)){
					echo '<meta name="keywords" content="'.$key_meta.'" />';
					echo "\n";
				}
			}elseif(is_tax('question')){
				$queried_object = get_queried_object(); 
				$term_id = $queried_object->term_id;
				$term_meta = get_term_meta( $term_id, '_question_tax', true );
				$term_meta = wp_parse_args( (array) $term_meta, array( 
						'seo_custom_keywords' => '',
						'seo_custom_desc'     => '',
					) 
				);
				$keywords = $term_meta['seo_custom_keywords'];
				$description = $term_meta['seo_custom_desc'];

				if($keywords){
					echo '<meta name="keywords" content="'.$keywords.'" />';
					echo "\n";
				}
				if($description){
					echo '<meta name="description" content="'.esc_attr(stripslashes($description)).'" />';
					echo "\n";
				}
			}
		}
	}

	function filter_document_title_parts($title){	//title的SEO
		if(is_single()){
			$object=get_queried_object();
			if($object->post_type=='aq'){
				$post_extend = get_post_meta( get_the_ID(), '_question', true );
				if(!empty($post_extend['seo_custom_title'])){
					$title['title']=$post_extend['seo_custom_title'];
				}
			}
		}elseif(is_tax('question')){
			$queried_object = get_queried_object(); 
			$term_id = $queried_object->term_id;
			$term_meta = get_term_meta( $term_id, '_question_tax', true );
			if(!empty($term_meta['seo_custom_title'])){
				$title['title']=$term_meta['seo_custom_title'];
			}
		}
		return $title;
	}
	
	function add_question(){	//发布问题
		if(empty($_POST['add_question_field'])||!wp_verify_nonce( $_POST['add_question_field'], 'add_question_action' )){
			wp_send_json(['code'=>0,'msg'=>'添加问题失败']);
		}
		$uid=get_current_user_id();
		if(empty($uid)){
			wp_send_json(['code'=>2,'msg'=>'登录后提问']);
		}
		$title=esc_sql($_POST['title']);
		if(empty($title)){
			wp_send_json(['code'=>0,'msg'=>'请填写提问内容']);
		}
		$category=esc_sql($_POST['category']);
		$content=esc_sql($_POST['content']);
		if(!empty(wpzt_pq('filter_content'))){
			$title=filter_content($title);
			$content=filter_content($content);
		}
		if(!empty(wpzt_pq('link_nofollow'))){
			$title=wp_rel_nofollow($title);
			$content=wp_rel_nofollow($content);
		}
		$post_data=[
			 'post_author'=>$uid,
			 'post_title'=>$title,
			 'post_content'=>$content,
			 'post_type'=>'aq',
			 'tax_input'=>[
				'question'=>$category
			 ]
		];
		if(wpzt_pq('send_status')){
			$post_data['post_status']='publish';
		}else{
			$post_data['post_status']='pending';
		}
		$flag=wp_insert_post($post_data);
		if(empty($flag)||is_wp_error($flag)){
			wp_send_json(['code'=>0,'msg'=>'问题发布失败，请联系站长']);
		}
		wp_send_json(['code'=>1,'msg'=>'问题已经发布']);
	}
	
	function add_static_file(){
		// touched
		// wp_register_script('jq21','https://s2.pstatp.com/cdn/expire-1-M/jquery/2.1.1/jquery.min.js');
		wp_register_script('wangeditor','https://s1.pstatp.com/cdn/expire-1-M/wangEditor/3.1.1/wangEditor.min.js');
		wp_register_script("bootstrap","https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js");
		wp_register_script('wdcommon',WPZT_PQ_JS.'/wdcommon.js');
		wp_register_script('letan',WPZT_PQ_JS.'/letan.js');
		wp_register_script('comment',WPZT_PQ_JS.'/comment.js');
		wp_enqueue_style("bootstrap","https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css");
		wp_enqueue_style("plyr",WPZT_PQ_CSS.'/ask-style.css');
		wp_enqueue_script('jq21');
		wp_enqueue_script("bootstrap");
		wp_enqueue_script('letan');
		
	}

	function wpzt_add_comment(){
		$pid=intval($_POST['pid']);
		$uid=get_current_user_id();
		$parent_commentid=intval($_POST['commentid']);
		$content=esc_sql($_POST['content']);
		if(!empty(wpzt_pq('filter_content'))){
			$content=filter_content($content);
		}
		if(!empty(wpzt_pq('link_nofollow'))){
			$content=wp_rel_nofollow($content);
		}
		if(empty($pid)||empty($uid)||empty($parent_commentid)||empty($content)){
			wp_send_json(['code'=>0,'msg'=>'回复失败']);
		}
		$insert_data=[
			'comment_content'=>$content,
			'user_id'=>$uid,
			'comment_parent'=>$parent_commentid,
			'comment_post_ID'=>$pid
		];
		wp_insert_comment($insert_data);
		wp_send_json(['code'=>1,'msg'=>'成功回复']);
	}
	
	function checklogin(){
		$uid=get_current_user_id();
		if(empty($uid)){
			wp_send_json(['code'=>0]);
		}else{
			wp_send_json(['code'=>1]);
		}
	}
	
	function get_comments_number($count,$post_id){
		global $wpdb;
		$query="select count(*) from {$wpdb->comments} where comment_post_ID={$post_id} and comment_approved=1";
		$count=$wpdb->get_var($query);
		return $count;
	}
	
	function search_filter_page($query) {
		if ($query->is_search && !$query->is_admin) {
				$query->set('post_type', ['post','aq']);
			}
			return $query;
		}
	
	function add_side_widget(){
		register_sidebar(array(
		  'name' => __( '问答插件侧边栏' ),
		  'id' => 'aq_side',
		  'description' => __( '问答插件侧边栏'),
		  'class'=>'',
		  'before_title' => '<h2>',
		  'after_title' => '</h2>',
		  'before_widget'=>'<div>',
		  'after_widget'=>'</div>'  
		));
	}
	
	function get_page_comment(){
		$page=intval($_POST['page']);
		$postid=intval($_POST['postid']);
		if(empty($page)||empty($postid)){
			wp_send_json(['code'=>0,'msg'=>'加载失败']);
		}
		global $post;
		$post = get_post($postid);
		setup_postdata( $post );
		$comment=wp_list_comments([
                'walker' => new MyCommentWalker(),
                'style'       => 'ul',
                'short_ping'  => true,
                'type'        => 'comment',
                'avatar_size' => '60',
                'format'    => 'html5',
				'page'=>$page,
				'per_page'=>get_option('comments_per_page'),
				'echo'=>false
            ]);
		wp_send_json(['code'=>1,'comment'=>$comment]);
	}
	
	function delete_comment(){
		$commentid=intval($_POST['id']);
		if(empty($commentid)){
			wp_send_json(['code'=>0,'msg'=>'获取评论信息失败']);
		}
		$uid=get_current_user_id();
		global $wpdb;
		$has_comment_query="select * from {$wpdb->prefix}comments where comment_ID={$commentid} and user_id={$uid}";
		$has_comment_flag=$wpdb->get_row($has_comment_query);
		if(empty($has_comment_flag)){
			wp_send_json(['code'=>0,'msg'=>'删除的评论不存在']);
		}
		$cidlist=$this->get_child_comment_list($commentid,[$commentid]);
		$cidlist_str=implode(',',$cidlist);
		
		$delete_comment="delete from {$wpdb->prefix}comments where comment_ID in ({$cidlist_str})";
		$delete_comment_meta="delete from {$wpdb->prefix}commentmeta where comment_id in ({$cidlist_str})";
		$wpdb->query($delete_comment);
		$wpdb->query($delete_comment_meta);
		wp_send_json(['code'=>1,'msg'=>'已经删除']);
	}
	
	function get_child_comment($parentid){	//获取所有子评论
		global $wpdb;
		$query="select comment_ID from {$wpdb->prefix}comments where comment_parent={$parentid}";
		$childidlist=$wpdb->get_col($query);
		return $childidlist;
	}
	
	function get_child_comment_list($parentid,$parentlist=[]){
		$list=$this->get_child_comment($parentid);
		if(empty($list)){
			return $parentlist;
		}else{
			$parentlist=array_merge($parentlist,$list);
			foreach($list as $v){
				return $this->get_child_comment_list($v,$parentlist);
			}
		}
	}
	
}
include_once WPZT_PQ_DIR."/inc/class/MyCommentWalker.php";
new wpzt_question();