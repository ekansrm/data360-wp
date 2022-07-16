<?php
namespace wpzt;

class Home{
	function __construct(){
		//CDN加速储存
		if (wpzt('cdn_open')) {
			add_action('wp_loaded',array($this,'wpzt_ob_start'));
		}
		if(wpzt('wpzt_add_alttitle')){
			add_filter( 'the_content',array($this,'image_alttitle'));//内容图片加title alt
		}
		//当搜索结果只有一篇时直接重定向到日志
		add_action('template_redirect',array($this,'searchone'));
		//删除菜单多余css class
		add_filter('nav_menu_css_class',array($this,'wpzt_css_attributes_filter'), 100, 1);
		add_filter('nav_menu_item_id',array($this,'wpzt_css_attributes_filter'), 100, 1);
		add_filter('page_css_class', array($this,'wpzt_css_attributes_filter'), 100, 1);
		//删除wordpress默认相册样式
		add_filter( 'use_default_gallery_style', '__return_false' );
		//文章自动nofollow
	//	add_filter( 'the_content', array($this,'addnofollow'));
		if(wpzt('wpzt_del_link')){
			add_filter('the_content',array($this,'wpzt_del_link'));//直接删除外链
		}
		//搜索结果排除所有页面
		add_filter('pre_get_posts', array($this,'search_filter_page'));
		/* 搜索关键词为空 */
		add_filter( 'request',array($this,'search_null'));
		//文章浏览量统计
		add_action('wp_head', array($this,'record_visitors'));  
		//禁止FEED
		if( wpzt('wpzt_feed') ) {
			add_action('do_feed', array($this,'wpzt_disable_feed'), 1);
			add_action('do_feed_rdf', array($this,'wpzt_disable_feed'), 1);
			add_action('do_feed_rss', array($this,'wpzt_disable_feed'), 1);
			add_action('do_feed_rss2', array($this,'wpzt_disable_feed'), 1);
			add_action('do_feed_atom', array($this,'wpzt_disable_feed'), 1);
		}
		//文章摘要
		add_filter('the_excerpt',array($this,'get_excerpt'));
		//seo处理
		add_theme_support( 'title-tag' );
		add_filter( 'run_wptexturize', '__return_false' );
		add_filter( 'document_title_separator', array($this,'filter_document_title_separator'), 10, 1 ); 
		add_filter( 'document_title_parts', array($this,'filter_document_title_parts'), 10, 1 );
		add_action( 'wp_head', array($this,'wpzt_seo_meta_action'));
		//去json*
		if( wpzt('wpzt_api') ){
			add_filter('rest_enabled', '__return_false');
			add_filter('rest_jsonp_enabled', '__return_false');
			remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
			//禁用REST API、移除wp-json链接
			add_filter('rest_enabled', '_return_false');
			add_filter('rest_jsonp_enabled', '__return_false');
			remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		}
		//去除wordpress前台顶部工具条
		show_admin_bar(false);
		//禁止前台加载语言包
		if( wpzt('wpzt_language') ){
			add_filter( 'locale', array($this,'wpzt_language') );
		}
		add_filter('nav_menu_item_title',array($this,'add_menu_content_1'),10,4);//有子菜单的菜单后面添加箭头
		add_action('init',array($this,'get_wpzt_haha'));
		remove_action('init', 'kses_init');//匿名评论时内容可以有html
		remove_action('set_current_user', 'kses_init');//匿名评论内容可以有html
		if(is_search()){
			add_filter('pre_get_posts', array($this,'wpzt_category_search'));
		}
		add_filter('get_edit_post_link',array($this,'change_edit_post_url'),10,2);//改变普通用户编辑文章的链接
		add_action( 'wp_enqueue_scripts', array($this,'remove_block_library_css') );
	}
	
	function wpzt_ob_start() {
        ob_start(array($this,'wpzt_cdn_replace'));
    }
    function wpzt_cdn_replace($html) {
        $local_host = home_url(); //博客域名
        $cdn_host = wpzt('cdn_url'); //CDN域名
        $cdn_exts = wpzt('cdn_file_format'); //扩展名（使用|分隔）
        $cdn_dirs = wpzt('cdn_mirror_folder'); //目录（使用|分隔）
        $cdn_dirs = str_replace('-', '\-', $cdn_dirs);
        if ($cdn_dirs) {
            $regex = '/' . str_replace('/', '\/', $local_host) . '\/((' . $cdn_dirs . ')\/[^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))([\"\\\'\s\?]{1})/';
            $html = preg_replace($regex, $cdn_host . '/$1$4', $html);
        } else {
            $regex = '/' . str_replace('/', '\/', $local_host) . '\/([^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))([\"\\\'\s\?]{1})/';
            $html = preg_replace($regex, $cdn_host . '/$1$3', $html);
        }
        return $html;
    }
	
	function image_alttitle( $imgalttitle ){
        global $post;
        $category = get_the_category();
        if(sizeof($category) > 0) {
            $flname=$category[0]->cat_name;
        } else {
            $flname = 'none';
        }
        $btitle = get_bloginfo();
        $imgtitle = $post->post_title;
        $imgUrl = "<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>";
        if(preg_match_all("/$imgUrl/siU",$imgalttitle,$matches,PREG_SET_ORDER)){
                if( !empty($matches) ){
                        for ($i=0; $i < count($matches); $i++){
                                $tag = $url = $matches[$i][0];
                                $j=$i+1;
                                $judge = '/title=/';
								$url=preg_replace('/(title)|(title="[.]{0,}")/','',$url);
								$url=preg_replace('/(alt)|(alt=\"[.]{0,}\")/','',$url);                           
                                $altURL = ' alt="'.$imgtitle.' ('. network_site_url( '/' ).') '.$flname.' 第'.$j.'张" title="'.$imgtitle.' '.$flname.' 第'.$j.'张-'.$btitle.'" ';
                                $url = rtrim($url,'>');
                                $url .= $altURL.'>';
                                $imgalttitle = str_replace($tag,$url,$imgalttitle);
                        }
                }
        }
        return $imgalttitle;
}
	
	function searchone() {
		if (is_search() && get_query_var('module') == '') {
			global $wp_query;
			$paged	= get_query_var('paged');
			if ($wp_query->post_count == 1 && empty($paged)) {
				wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
			}
		}
	}
	
	function wpzt_css_attributes_filter($classes) {
		return is_array($classes) ? array_intersect($classes, array('current-menu-item','current-post-ancestor','current-menu-ancestor','current-menu-parent','menu-item-has-children','menu-item')) : '';
	}
	
	function addnofollow( $content ) {
		global $post;
		$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>(.*?)<\/a>/i";
		$replacement = '<a$1href=$2$3.$4$5 data-fancybox="images" $6>$7</a>';
		$content = preg_replace($pattern, $replacement, $content);
		//文章自动nofollow
		$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
		if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
			if( !empty($matches) ) {
	   
				$srcUrl = get_option('siteurl');
				for ($i=0; $i < count($matches); $i++)
				{
					$tag = $matches[$i][0];
					$tag2 = $matches[$i][0];
					$url = $matches[$i][0];
	   
					$noFollow = '';
					$pattern = '/target\s*=\s*"\s*_blank\s*"/';
					preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
					if( count($match) < 1 )
						$noFollow .= ' target="_blank" ';
	   
					$pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
					preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
					if( count($match) < 1 )
						$noFollow .= ' rel="nofollow" ';
	   
					$pos = strpos($url,$srcUrl);
					if ($pos === false) {
						$tag = rtrim ($tag,'>');
						$tag .= $noFollow.'>';
						$content = str_replace($tag2,$tag,$content);
					}
				}
			}
		}
		$content = str_replace(']]>', ']]>', $content);
		return $content;
	}
	
	function wpzt_del_link($content){
		$hostname=$_SERVER['SERVER_NAME'];
		preg_match_all("/<a[^>]*>(.*?)<\/a>/i", $content, $matches, PREG_SET_ORDER);
		if($matches){
			foreach($matches as $v){
				$pos=strpos($v[0],$hostname);
				if($pos===false){
					$content=str_replace($v[0], $v[1], $content);
				}
			}
		}
		return $content;
	}
	
	function search_filter_page($query) {
		if ($query->is_search && !$query->is_admin) {
			$query->set('post_type', 'post');
		}
		return $query;
	}
	
	function search_null( $query_variables ) {
		if (isset($_GET['s'])) {
			if (empty($_GET['s']) || ctype_space($_GET['s'])) {
				wp_redirect( home_url() );
				exit;
			}
		}
		return $query_variables;
	}
	
	function record_visitors(){
		if (is_singular()) {global $post;
		 $post_ID = $post->ID;
		  if($post_ID) 
		  {
			  $post_views = (int)get_post_meta($post_ID, 'views', true);
			  if(!update_post_meta($post_ID, 'views', ($post_views+1))) 
			  {
				add_post_meta($post_ID, 'views', 1, true);
			  }
		  }
		}
	}
	
	function wpzt_disable_feed() {
		wp_die(__('<h1>Feed已经关闭, 请访问网站<a href="'.get_bloginfo('url').'">首页</a>!</h1>'));
	}
	
	function get_excerpt($post_excerpt){
		global $post;
		$meta_data = get_post_meta($post->ID, 'extend_info', true); 
		$post_abstract = isset($meta_data['post_abstract']) ?$meta_data['post_abstract'] : '';
		if(!empty($post_abstract)){
			return mb_strimwidth($post_abstract, 0, 120, '...');
		}elseif( has_excerpt() ){
			$description = get_the_excerpt();
			return mb_strimwidth($description, 0, 120, '...');
		}else{
			return $this->get_post_excerpt($post, 120, '...');
		}
	}
	
	//获取日志摘要
	function get_post_excerpt($post=null, $excerpt_length=240){
		$post = get_post($post);
		if ( empty( $post ) ) {
			return '';
		}

		$post_excerpt = $post->post_excerpt;
		if($post_excerpt == ''){
			$post_content   = strip_shortcodes($post->post_content);
			//$post_content = apply_filters('the_content',$post_content);
			$post_content   = wp_strip_all_tags( $post_content );
			$excerpt_length = apply_filters('excerpt_length', $excerpt_length);	 
			$excerpt_more   = apply_filters('excerpt_more', ' ' . '...');
			$post_excerpt   = $this->get_first_p($post_content); // 获取第一段
			if(mb_strwidth($post_excerpt) < $excerpt_length*1/3 || mb_strwidth($post_excerpt) > $excerpt_length){ // 如果第一段太短或者太长，就获取内容的前 $excerpt_length 字
				$post_excerpt = mb_strimwidth($post_content,0,$excerpt_length,$excerpt_more,'utf-8');
			}
		}

		$post_excerpt = wp_strip_all_tags( $post_excerpt );
		$post_excerpt = trim( preg_replace( "/[\n\r\t ]+/", ' ', $post_excerpt ), ' ' );

		return $post_excerpt;
	}
	//获取第一段
	function get_first_p($text){
		if($text){
			$text = explode("\n",strip_tags($text)); 
			$text = trim($text['0']); 
		}
		return $text;
	}

	function get_wpzt_haha(){
		$hostname=$_SERVER['SERVER_NAME'];
		if(\wpzt\Cache::get('wpzt_hehe')){//记录一下时间
			return;	
		}else{
			 if(get_option('wpzt_hehe')){
				 $rc=get_option('wpzt_hehe');
				 $mintime=$rc['time']+864000;
				 $maxtime=$rc['time']+1296000;
				 if($mintime<time()&&$maxtime>time()){
					 $this->set_haha_info();
				 }
				 if($maxtime<time()){
					 $this->get_haha_info();
				 }
				 $host=$rc['host'];
				 $hostname=str_replace('www.','',$hostname);
				 $host=str_replace('www.','',$host);
				 if($host!=$hostname){
					 $this->get_haha_info();
				 }
				 if($rc['pid']!=2576){
					 delete_option('wpzt_hehe');
					 wp_die(base64_decode('6K+35Y67PGEgaHJlZj0iaHR0cDovL3d3dy53cHp0Lm5ldCI+5Li76aKY55uS5a2Q5pu05o2i5o6I5p2D5Z+f5ZCNPC9hPg=='));
				 }
				 \wpzt\Cache::set('wpzt_hehe',$rc);
			 }else{
				$this->get_haha_info($hostname);
			 }
		}
	}
	
	function get_haha_info(){
		$hostname=$_SERVER['SERVER_NAME'];
		$url="https://www.wpzt.net/wp-json/wpzt/v1/checkauth";
		$request = new \WP_Http;
		$data=['pid'=>2576,'host'=>$hostname];
		$result=$request->request($url,['method'=>'POST','body'=>$data,'headers'=>['application/json']]);
		if(is_wp_error($result)){
					
			wp_die(base64_decode('572R57uc6L+e5o6l5aSx6LSlLOivt+eojeWQjuWGjeivlQ=='));
		}else{
			$result=json_decode($result['body'],true);
			if($result['code']==1){
					$hehe_str=['host'=>$hostname,'time'=>time(),'pid'=>2576];
					update_option('wpzt_hehe',$hehe_str);
					\wpzt\Cache::set('wpzt_hehe',$hehe_str);
				}else{
						
					wp_die(base64_decode('5o6I5p2D5aSx6LSl77yM6K+35Y67PGEgaHJlZj0iaHR0cDovL3d3dy53cHp0Lm5ldCI+5Li76aKY55uS5a2QPC9hPuiOt+WPluaOiOadgw=='));
				}
			}
	}
	
	function set_haha_info(){
		$hostname=$_SERVER['SERVER_NAME'];
		$url="https://www.wpzt.net/wp-json/wpzt/v1/checkauth";
		$request = new \WP_Http;
		$data=['pid'=>2576,'host'=>$hostname];
		$result=$request->request($url,['method'=>'POST','body'=>$data,'headers'=>['application/json']]);
		if(is_wp_error($result)){
			return;
		}else{
			$result=json_decode($result['body'],true);
			if($result['code']==1){
				$hehe_str=['host'=>$hostname,'time'=>time(),'pid'=>2576];
				update_option('wpzt_hehe',$hehe_str);
				\wpzt\Cache::set('wpzt_hehe',$hehe_str);
			}
		}
	}
	
	function filter_document_title_separator( $var ) {
		$option_sep = wpzt('seo_sep');
		$var = isset( $option_sep ) ? $option_sep : $var;
		return $var;
	}

	function filter_document_title_parts( $title ) { 

		global $paged, $page, $post;

		if( is_home() || is_front_page() ){
			$title_home = wpzt('seo_home_title');
			$title['title'] = ( isset( $title_home ) && !empty( $title_home )) ? $title_home : get_bloginfo('name');
		}
		else if( is_single() || is_page() ){
			$post_extend = get_post_meta( get_the_ID(), 'extend_info', true );
			$title['title'] = ( isset( $post_extend['seo_custom_title'] ) && !empty( $post_extend['seo_custom_title'] ) ) ? $post_extend['seo_custom_title'] : get_the_title( $post->ID );
		}
		else if( is_category() ){
			$cat_id = get_query_var('cat');
			$term_meta = get_term_meta( $cat_id, 'category_options', true );
			$term_meta = wp_parse_args( (array) $term_meta, array( 
					'seo_custom_title' => '',
				) 
			);
			$title_category = $term_meta['seo_custom_title'];
			$title['title'] = ( isset( $title_category ) && !empty( $title_category ) ) ? $title_category : get_cat_name( $cat_id );
		}
		else if(is_tag()){
			$tag_id = get_query_var('tag_id');
			$term_meta = get_term_meta( $tag_id, 'tag_options', true );
			$term_meta = wp_parse_args( (array) $term_meta, array( 
					'seo_custom_title' => '',
				) 
			);
			$title_tag = $term_meta['seo_custom_title'];
			$title['title'] = ( isset( $title_tag ) && !empty( $title_tag ) ) ? $title_tag : single_tag_title( '', false );
		}
		else if( is_author() && ! is_post_type_archive() ){
			$author = get_queried_object();
			if ( $author ) {
				$title['title'] = $author->display_name;
			}
		}
		else if( is_tax('special') ){
			$queried_object = get_queried_object(); 
			$term_id = $queried_object->term_id;

			$term_meta = get_term_meta( $term_id, '_custom_special_options', true );
			$term_meta = wp_parse_args( (array) $term_meta, array( 
					'seo_custom_title' => '',
				) 
			);
			$title_special= $term_meta['seo_custom_title'];
			$title['title'] = ( isset( $title_special ) && !empty( $title_special ) ) ? $title_special : $queried_object->name;
		}
		else if( is_search() ) {
			$title['title'] = "搜索结果：".get_query_var( 's' );
		}
		else if ( is_404() ) {
			$title['title'] = __( '未找到页面' );
		}

		return $title; 
	}

	function wpzt_seo_meta_action(){
		
		$pages=get_query_var('paged');
		if( (is_single() || is_page()) && $pages<2 ){
			global $post;
			$post_extend = get_post_meta( get_the_ID(), 'extend_info', true );
			if( wpzt('seo_auto_des') ) :
			$post_desc_num = wpzt('seo_auto_des_num',120);
			$seo_auto_des = wpzt('seo_auto_des',true);
			endif;
			$seo_auto_keywords = wpzt('seo_auto_keywords',true);

			$tag = '';
			$tags=get_the_tags();
			if( $tags ){
				foreach($tags as $val){
					$tag.=','.$val->name;
				}
			}
			$tag=ltrim($tag,',');
			$key_meta = isset($post_extend['seo_custom_keywords']) ? $post_extend['seo_custom_keywords'] : '';
			$des_meta = isset($post_extend['seo_custom_desc']) ? $post_extend['seo_custom_desc'] : '';

			$pt = preg_replace('/\s+/','',strip_tags($post->post_content));
			$excerpt = mb_strimwidth($pt,0,$post_desc_num, '', get_bloginfo( 'charset' ) );
			
			if( empty($key_meta) && $seo_auto_keywords && isset($tag) ) $keywords=$tag;
			else $keywords=$key_meta;

			if( empty($des_meta) && $seo_auto_keywords ) $description=$excerpt;
			else $description=$des_meta;

			if($keywords){  
				echo '<meta name="keywords" content="'.$keywords.'" />';
				echo "\n";
			}

			if($description){   
				echo '<meta name="description" content="'.esc_attr($description).'" />';
				echo "\n";
			}
		}
		
		if( (is_home() || is_front_page()) && !is_paged() ){
			
			$keywords = wpzt('seo_home_keywords');
			$description = wpzt('seo_home_desc');

			if($keywords){  
				echo '<meta name="keywords" content="'.$keywords.'" />';
				echo "\n";
			}
			if($description){
				echo '<meta name="description" content="'.esc_attr(stripslashes($description)).'" />';
				echo "\n";
			}   
		}
		
		if( ( is_category() ) && !is_paged()){

			$queried_object = get_queried_object(); 
			$term_id = $queried_object->term_id;

			$term_meta = get_term_meta( $term_id, 'category_options', true );
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
		
		if( ( is_tag() ) && !is_paged()){

			$queried_object = get_queried_object(); 
			$term_id = $queried_object->term_id;

			$term_meta = get_term_meta( $term_id, 'tag_options', true );
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

		if(  is_tax('special') && !is_paged()){

			$queried_object = get_queried_object(); 
			$term_id = $queried_object->term_id;

			$term_meta = get_term_meta( $term_id, '_custom_special_options', true );
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

	function wpzt_language($locale) {
		//$locale = ( is_admin() ) ? $locale : 'en_US';
		return 'en_US';
	}
	function add_menu_content_1($title, $item, $args, $depth){	
		if($item->menu_item_parent==0){		//处理顶级菜单
				 global $wpdb;
				 $has_children = $wpdb->get_var("SELECT COUNT(meta_id) FROM {$wpdb->prefix}postmeta WHERE meta_key='_menu_item_menu_item_parent' AND meta_value='" . $item->ID . "'"); 
				 if($has_children){
					return $title.'<i class="iconfont icon-jiantoux2-copy"></i>';
				 }else{
					 return $title;
				 }
		}else{	//处理二级菜单
			if(wpzt('menu_style')==1){
				return $title;
			}else{
				$output="";
				$meta = get_post_meta( $item->ID, '_menu_options', true );
				if(!empty($meta['icon']['url'])){
					$output.="<div class='menu-img'><img src='{$meta['icon']['url']}'/></div>";
				}
				$output.="<div class='menu-title'>{$title}</div>";			
				$output.="<div class='menu-post-num'>{$item->description}</div>";
				return $output;
			}
		}	
	}
	
	function wpzt_category_search($query){
		if($query->is_main_query()){
			$cat=\wpzt\form\Request::get('cat');
			if(\wpzt\form\Validate::isInt($cat)&&empty($cat)){
				 $tax_query = array(
								  array(
									'taxonomy'=>'category', //可换为自定义分类法
									'field'=>'term_id',
									'operator'=>'IN',
									'terms'=>array($cat)
								  )
								);
				$query->set( 'tax_query', $tax_query );
			}
		}
		return $query;
	}
	
	function change_edit_post_url($link,$postid){
		if(current_user_can( 'manage_options' )){
			return $link;
		}
		$link=get_edit_post($postid);
		return $link;
	}
	
	function remove_block_library_css(){
		wp_dequeue_style( 'wp-block-library' );
	}
	
}