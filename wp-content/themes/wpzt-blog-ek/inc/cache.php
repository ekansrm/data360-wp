<?php
use wpzt\Cache;
$option=get_option("_cs_options");

if($option['cache-type']==1||is_admin()||empty($option['cache-type'])){
Cache::init([
	'type'   => 'File',
	'path'	=>WPZT_CACHE,
	'prefix'=>'wpzt_',
	'expire'=>0
]);
}elseif($option['cache-type']==2){
	Cache::init([
		'type'=>'Redis',
		'host'=>$option['cache-server'],
		'port'=>$option['cache-port'],
		'password'=>$option['cache-pwd']
	]);
}elseif($option['cache-type']==3){
	Cache::init([
		'type'=>'Memcached',
		'prefix'=>'wpzt_',
		'host'=>$option['cache-server'],
		'port'=>$option['cache-port'],
		'username'=>$option['cache-user'],
		'password'=>$option['cache-pwd']
		
	]);
}
if(!Cache::has('wpzt_option')){
	Cache::set('wpzt_option',$option,0);
}

function admin_clear_cache(){//清除高速缓存
	$cache_type=wpzt('cache-type');
	switch($cache_type){
		case 3:
			$dcache=Cache::connect([
				'type'=>'Memcached',
				'prefix'=>'wpzt_',
				'host'=>wpzt('cache-server'),
				'port'=>wpzt('cache-port'),
				'username'=>wpzt('cache-user'),
				'password'=>wpzt('cache-pwd')
			]);break;
		case 2:
			$dcache=Cache::connect([
					'type'=>'Redis',
					'host'=>wpzt('cache-server'),
					'port'=>wpzt('cache-port'),
					'password'=>wpzt('cache-pwd')
				]);break;
		case 1:
		default:
			$dcache=Cache::connect([
				'type'   => 'File',
				'path'	=>WPZT_CACHE,
				'prefix'=>'wpzt_',
				'expire'=>0
			]);	
	}
	$dcache->clear();
}

function get_cache_query($args){
	$key=md5(json_encode($args));
	if(Cache::get($key)){
		return Cache::get($key);
	}else{
		$the_query=new WP_Query($args);
		Cache::set($key,$the_query,3600);
		return $the_query;
	}
}

function get_cache_terms($args){
	$key=md5('terms'.json_encode($args));
	if(Cache::get($key)){
		return Cache::get($key);
	}else{
		$terms=get_terms($args);
		Cache::set($key,$terms);
		return $terms;
	}
}

function get_cache_bookmarks($args){
	$key=md5('bookmarks'.json_encode($args));
	if(Cache::get($key)){
		return Cache::get($key);
	}else{
		$links=get_bookmarks($args);
		Cache::set($key,$links);
		return $links;
	}
}

function get_post_array($args){
	$key='post'.md5(json_encode($args));
	if(Cache::get($key)){
		return Cache::get($key);
	}else{
		$the_query=get_cache_query($args);
		$postarray=[];
		if($the_query->have_posts()){
			while($the_query->have_posts()){
				$the_query->the_post();
				$postkey=$the_query->current_post;
				$pid=get_the_ID();
				$postarray[$postkey]['id']=$pid;
				$postarray[$postkey]['title']=get_the_title();
				$postarray[$postkey]['link']=get_the_permalink();
				$postarray[$postkey]['excerpt']=get_the_excerpt();
				$postarray[$postkey]['img']=get_post_img(850,480);
				$postarray[$postkey]['time']=get_the_time('Y-m-d',$pid);
				$postarray[$postkey]['meta']=wpzt_post($pid);
				$postarray[$postkey]['count']=$the_query->found_posts;
			}
			wp_reset_postdata();
		}
		Cache::set($key,$postarray,7200);
		return $postarray;
	}
}

function get_cache_post($post_id){
	if(Cache::get('post'.$post_id)){
		return Cache::get('post'.$post_id);
	}else{
		$post=get_post($post_id);
		Cache::set('post'.$post_id,$post,0);
		return $post;
	}
}

function get_menu_array($menuslug){	//获取菜单树状数组
	$key='menu'.$menuslug.md5($menuslug);
	if(Cache::get($key)){
		return Cache::get($key);
	}else{
		 $location=get_nav_menu_locations();
		 $menu=wp_get_nav_menu_object($location[$menuslug]);
		 $menus=wp_get_nav_menu_items($menu->term_id);
		 $menuarray=[];
		 $menulist=[];
		 if($menus){
			 foreach($menus as $k=>$v){
				 $menulist[$k]=[		//缩减一下数组
						'id'=>$v->ID,
						'name'=>$v->title,
						'link'=>$v->url,
						'target'=>$v->target,
						'desc'=>$v->description,
						'parentid'=>$v->menu_item_parent,
						'meta'=>get_post_meta( $v->ID, '_menu_options', true )
				 ];
			 }
		 }
		 $menuarray=get_menu_item($menulist,0);	
		 Cache::set($key,$menuarray);
		 return $menuarray;
	}
}

function get_menu_item($data,$pid = 0){
      $result = array();
      foreach($data as $v){
            if($v['parentid'] == $pid){
                $v['child'] = get_menu_item($data,$v['id']);
                $result[] = $v;
            }
       }
      return $result;
}

//页脚菜单
function get_footer_menu_link($term_id){
	$key='menu_footer_'.$term_id;
	if(Cache::get($key)){
		return Cache::get($key);
	}else{
		$menus=wp_get_nav_menu_items($term_id);
		$menulist=[];
		if(!empty($menus)){
			foreach($menus as $k=>$v){
				$menulist[$k]=[
					'name'=>$v->title,
					'link'=>$v->url,
					'target'=>$v->target
				];
			}
		}
		return $menulist;
	}
}


//缓存主循环
function get_main_loop_cache($pre,$wp_query){
	if(!$wp_query->is_main_query()){//判断不为主循环
		return $pre; //pre默认为null,继续执行查询
	}

	$key= md5(maybe_serialize($wp_query->query_vars));//缓存的key
	if(Cache::get($key)){
		$wp_query->max_num_pages=Cache::get($key)['max_num_pages'];;//设置分页信息
		$wp_query->found_posts=Cache::get($key)['found_posts'];
		return Cache::get($key)['posts'];
	}else{
		return $pre;
	}
}

//获取缓存菜单
function get_menu_cache($nav_menu,$args){
	$key=md5('menu'.maybe_serialize($args));
	if(Cache::get($key)){
		return Cache::get($key);
	}else{
		return $nav_menu;
	}
}
if(!is_admin()){
	
}

function set_main_loop_cache($posts,$wp_query){
	if(!$wp_query->is_main_query()){
		return $posts;
	}
	
	$key= md5(maybe_serialize($wp_query->query_vars));//缓存的key
	if(Cache::get($key)){
		return $posts;
	}else{
		$data['posts']=$posts;
		$data['max_num_pages']=$wp_query->max_num_pages;//主循环分页存入
		$data['found_posts']=$wp_query->found_posts;
		Cache::set($key,$data,0);
		return $posts;
	}
}

//设置菜单缓存
function set_menu_cache($nav_menu,$args){
	$key=md5('menu'.maybe_serialize($args));
	Cache::set($key,$nav_menu,0);
	return $nav_menu;
}
if(!is_admin()){
	if(empty($_GET['act'])){
		add_filter('posts_pre_query','get_main_loop_cache',10,2);
		add_filter('posts_results','set_main_loop_cache',10,2);
	}
	add_filter('pre_wp_nav_menu','get_menu_cache',10,2);
	add_filter('wp_nav_menu','set_menu_cache',10,2);
}

function request_uri() {
      if (isset($_SERVER['REQUEST_URI'])){
          $uri = $_SERVER['REQUEST_URI'];
     }else{
         if (isset($_SERVER['argv'])){
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
        }else{
             $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
         }
     }
     return $uri;
 }

function begin_page_cache(){
    $key=md5('page'.request_uri());
    if(Cache::has($key)){
        echo Cache::get($key);
        return true;
    }else{
       // ob_flush();
        ob_start();
		return false;
    }
}

function end_page_cache(){
     $key=md5('page'.request_uri());
     $echo = ob_get_flush();
     Cache::set($key,$echo);
}

