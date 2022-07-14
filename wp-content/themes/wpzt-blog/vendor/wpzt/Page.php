<?php
namespace wpzt;
use wpzt\Cache;

class Page{
	
	function __construct(){
		add_action( 'load-themes.php', array($this,'add_allpages'));
	}
	
	function add_allpages() {
		global $pagenow;
		//判断是否为激活主题页面
		if ('themes.php' == $pagenow && isset( $_GET['activated'])){	//以下添加需要自动新建的页面
			Cache::clear();
			$this->add_page('专题页','special','page/page-special.php'); 
			$this->add_page('标签云','tagslist','page/page-tag.php');
		}
	}
	
	function add_page($title,$slug,$page_template=''){
		$allPages = $this->get_cache_pages();//获取所有页面
		$exists = false;
		if(!empty($allPages)){
			foreach( $allPages as $page ){
				//通过页面别名来判断页面是否已经存在
				if( strtolower( $page->post_name ) == strtolower( $slug ) ){
					$exists = true;
				}
			}
		}
		if( $exists == false ) {
			$new_page_id = wp_insert_post(
				array(
					'post_title' => $title,
					'post_type'     => 'page',
					'post_name'  => $slug,
					'comment_status' => 'closed',
					'ping_status' => 'closed',
					'post_content' => '',
					'post_status' => 'publish',
					'post_author' => 1,
					'menu_order' => 0
				)
			);
			//如果插入成功 设置模板
			if($new_page_id && $page_template!=''){
				//保存页面模板信息
				update_post_meta($new_page_id, '_wp_page_template',  $page_template);
			}
		}
	}
	
	function get_cache_pages(){//获取所有页面
		if(Cache::get('allpages')){
			return Cache::get('allpages');
		}else{
			$allpages=get_pages();
			Cache::set('allpages',$allpages);
			return $allpages;
		}
	}
	
	
}
