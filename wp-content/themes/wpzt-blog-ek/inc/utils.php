<?php
	function wpzt_paginate($count,$current=1,$p=2){
		if($count==1){return;}//仅有一页不用分页
		if($current>1){echo_page($current-1,'上一页','<i class="iconfont icon-shangyiye"></i>');}
		if($current>$p+1){echo_page(1,'首页');}
		if($current>$p+2){echo "<a class='page-numbers' href='javascript:void(0);'>...</a>";}
		for($i=$current-$p;$i<=$current+$p;$i++){
			if($i>0&&$i<=$count){
				if($i==$current){
					echo "<a class='active' href='javascript:void(0);'>{$i}</a>";
				}else{
					echo_page($i);
				}
			}	
		}
		if($current<$count-$p-1){echo "<a class='page-numbers' href='javascript:void(0);'>...</a>";}
		if($current<$count-$p){echo_page($count,'尾页');}
		if($current<$count){echo_page($current+1,'下一页','<i class="iconfont icon-xiayiye"></i>');}
	}

	function echo_page($i,$title=null,$icon=""){
		$title=$title?:"第{$i}页";
		$icon=$icon?:$i;
		$link=get_pagenum_link($i);
		echo "<a title='{$title}' href='{$link}'>{$icon}</a>";
	}


	function letan_page($query=null,$current=0,$p=2){
		if(empty($query)){
			global $wp_query;
			$query=$wp_query;
		}
		$count=$query->max_num_pages;
		if(empty($current)){
			$current=get_query_var('paged')?:1;
		}
		wpzt_paginate($count,$current,$p);
	}

		//缩略图^_^
	function get_post_img($width=400, $height=200, $thumbnail=''){
		if(empty($thumbnail)){
			$thumbnail=wpzt('thumbnail');
		}
		$default_timthumb	= wpzt('timthumb');
		if(!empty($default_timthumb)){
			 $timthumb_gallery	= explode( ',', $default_timthumb );
			 $timthumb_num=count($timthumb_gallery)-1;
			 $random= mt_rand(0, $timthumb_num);
			 $timthumb= wp_get_attachment_image_src($timthumb_gallery[$random],'full')[0];
		}else{
			$timthumb=WPZT_IMG.'/no-image.png';
		}
		$thumbnail = wpzt('thumbnail');
		$cdn_type = wpzt('cdn_type');
		$cdn_switch = wpzt('cdn_switch');
		global $post;
		$title = $post->post_title;
		$post_img = '';
		if( has_post_thumbnail() ){
			$timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
			$src = $timthumb_src[0];
			if( $cdn_switch ){
				if( $cdn_type == 'qiniu' ){
					$post_img_src = "$src?imageView2/1/w/$width/h/$height/q/100";
				}elseif( $cdn_type == 'alioss' ){
					$post_img_src = "$src?x-oss-process=image/resize,m_fill,w_$width,h_$height";
				}elseif( $cdn_type == 'txcos' ){
					$post_img_src = "$src?imageView2/1/w/$width/h/$height/q/100";
				}
			}else{
				if ($thumbnail == 'timthumb') {
					$post_img_src = WPZT_URL."/timthumb.php?src={$src}&w={$width}&h={$height}zc=1&q=100";
				} else {
					$post_img_src = "$src";
				}
			}
		}else{
			ob_start();
			ob_end_clean();
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches ,PREG_SET_ORDER);
			$cnt = count( $matches );
			if($cnt>0){
				$src = $matches[0][1];
			}else{ // thumb
				
				$src = $timthumb;
			}
			if( $cdn_switch ){
				if( $cdn_type == 'qiniu' ){
					$post_img_src = "$src?imageView2/1/w/$width/h/$height/q/100";
				}elseif( $cdn_type == 'alioss' ){
					$post_img_src = "$src?x-oss-process=image/resize,m_fill,w_$width,h_$height";
				}elseif( $cdn_type == 'txcos' ){
					$post_img_src = "$src?imageView2/1/w/$width/h/$height/q/100";
				}
			}else{
				if ($thumbnail == 'timthumb') {
					$post_img_src = WPZT_URL."/timthumb.php?src={$src}&w={$width}&h={$height}zc=1&q=100";
				} else {
					$post_img_src = "$src";
				}
			}
		}
		$post_img =$post_img_src;
		return $post_img;	
	}

	

	function wpzt_Enc($data, $key = 'j-fe25%z+u#85-r.i', $expire = 0) {
		$key = md5($key);
		$data = base64_encode($data);
		$x = 0;
		$len = strlen($data);
		$l = strlen($key);
		$char = '';

		for ($i = 0; $i < $len; $i++) {
			if ($x == $l)
				$x = 0;
			$char .= substr($key, $x, 1);
			$x++;
		}
		$str = sprintf('%010d', $expire ? $expire + time() : 0);
		for ($i = 0; $i < $len; $i++) {
			$str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
		}
		return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
	}
	function wpzt_Dec($data, $key = 'j-fe25%z+u#85-r.i') {
		$key = md5($key);
		$data = str_replace(array('-', '_'), array('+', '/'), $data);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		$data = base64_decode($data);
		$expire = substr($data, 0, 10);
		$data = substr($data, 10);

		if ($expire > 0 && $expire < time()) {
			return '';
		}
		$x = 0;
		$len = strlen($data);
		$l = strlen($key);
		$char = $str = '';

		for ($i = 0; $i < $len; $i++) {
			if ($x == $l)
				$x = 0;
			$char .= substr($key, $x, 1);
			$x++;
		}

		for ($i = 0; $i < $len; $i++) {
			if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
				$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
			} else {
				$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
			}
		}
		return base64_decode($str);
	}

	function get_user_avatar($uid){
		$useravatar=get_user_meta($uid,'avatar',true);
		if($useravatar){
			return $useravatar;
		}else{
			return WPZT_IMG.'/avatar.png';
		}
	}

	
	
	function shortnum($number){
		$length = strlen($number);  //数字长度
		if($length > 8){ //亿单位
			$str = substr_replace(strstr($number,substr($number,-7),' '),'.',-1,0)."亿";
		}elseif($length > 4){ //万单位
			$str = substr_replace(strstr($number,substr($number,-3),' '),'.',-1,0)."万";
		}else{
			return $number;
		}
		return $str;
	}
	
	function get_views($pid){
		$views=get_post_meta($pid,'views',true);
		$views=empty($views)?0:$views;
		return shortnum($views);
	}
	function get_like($pid){
		$like=get_post_meta($pid,'like',true);
		$mylike=empty($like)?0:$like['like'];
		return shortnum($mylike);
	}
	function get_nolike($pid){
		$like=get_post_meta($pid,'like',true);
		$mylike=empty($like)?0:$like['nolike'];
		return shortnum($mylike);
	}
	
	function timeago($ptime) {		//文档时间显示
			$ptime = strtotime($ptime);
			$etime = time() - $ptime;
			if ($etime < 1) return '刚刚';
			$interval = array(
				 12 * 30 * 24 * 60 * 60 => '年前',
				 30 * 24 * 60 * 60 => '个月前',
				 7 * 24 * 60 * 60 => '周前',
				24 * 60 * 60 => '天前',
				60 * 60 => '小时前',
				60 => '分钟前',
				1 => '秒前'
			);
			foreach ($interval as $secs => $str) {
				$d = $etime / $secs;
				if ($d >= 1) {
					$r = round($d);
					return $r . $str;
				}
			}
		}
	
	
	function add_title($title){//修改自定义页面的标题
		add_filter( 'document_title_parts', function($oldtitle)use($title){ $oldtitle['title']=$title;return $oldtitle;});
	}

	function get_login_url(){
		$current_url=\wpzt\form\Request::url();
		$login_url=home_url('login');
		return add_query_arg(['redirect_to'=>$current_url],$login_url);
	}
	

	function get_month_post_num(){	//月文章更新数量
		$year=date('Y');
		$month=date('m');
		$args=[
			'year'=>$year,'monthnum'=>$month,'fields'=>'ids'
		];
		$the_query = get_cache_query($args);
		return $the_query->found_posts;
	}
	
	function get_week_post_num(){	//周文章更新数量
		$date_query = array(
			array(
				'after'=>'1 week ago'
			)
		);
		$args = array(
		'date_query' => $date_query,
		'no_found_rows' => true,
		'suppress_filters' => true,
		'fields'=>'ids'
		);
		$the_query = get_cache_query( $args );
		return  $the_query->found_posts;
	}
	
	function get_lastday_post_num(){	//昨天天文章更新数量
		$time=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
		$year=date('Y',$time);
		$month=date('m',$time);
		$day=date('d',$time);
		$args=[
			'year'=>$year,'monthnum'=>$month,'day'=>$day,'fields'=>'ids'
		];
		$the_query=get_cache_query($args);
		return $the_query->found_posts;
	}
			
	function get_breadcrumb(){	//面包屑
		$str="<ul class='breadcrumb'>
                <span>
                  <i class='iconfont icon-dingwei'></i>当前位置：</span>
				   <li><a href='".home_url()."' title='首页'>首页</a></li>&gt; ";
		if(is_search()){
			global $wp_query,$s;
			$str.="<li>#".$s."共搜索到".$wp_query->found_posts."篇文章</li>";
		}elseif(is_tag()){
			$term=get_queried_object();
			$str.="<li>TAG:".$term->name."</li>";
		}elseif(is_category()){
			$term=get_queried_object();
			$catstr='<li>'.$term->name.'</li>';
			while($term->parent!=0){
				$term=get_term($term->parent);
				$catstr="<li><a href='".get_category_link($term->term_id)."' title='".$term->name."'>".$term->name."</a></li>&gt;".$catstr;
			}
			$str.=$catstr;
		}elseif(is_single()){
			$category=get_the_category();
			$term=$category[0];
			$catstr='<li>'.$term->name.'</li>';
			while($term->parent!=0){
				$term=get_term($term->parent);
				$catstr="<li><a href='".get_category_link($term->term_id)."' title='".$term->name."'>".$term->name."</a></li>&gt;".$catstr;
			}
			$str.=$catstr;
		}elseif(is_author()){
			$author = get_queried_object();
			$str.="<li>#作者:".$author->display_name."</li>";
		}elseif(is_tax('special')){
			$term=get_queried_object();
			$catstr='<li>'.$term->name.'</li>';
			while($term->parent!=0){
				$term=get_term($term->parent);
				$catstr="<li><a href='".get_term_link($term->term_id)."' title='".$term->name."'>".$term->name."</a></li>&gt;".$catstr;
			}
			$str.=$catstr;
		}
		$str.="</ul>";
		return $str;		  
	}
	
	function get_role_grade($user){//获取用户等级1-5 Administrator Editor Author Contributor Subscriber
		$role= array_shift($user->roles);
		switch($role){
			case 'administrator':return 1;
			case 'editor':return 2;
			case 'author':return 3;
			case 'contributor':return 4;
			case 'subscriber':return 5;
			default:return 5;
		}
	}
	
	function get_role_name($user){
		$role= array_shift($user->roles);
		switch($role){
			case 'administrator':return '管理员';
			case 'editor':return '编辑';
			case 'author':return '作者';
			case 'contributor':return '贡献者';
			case 'subscriber':return '订阅者';
		}
	}
	
	function user_can_sendpost($user){
		$grade=get_role_grade($user);
		if($grade<=4){
			return true;
		}else{
			return false;
		}
	}

	function get_post_status_name($status){
		switch($status){
			case 'publish':return '已发布';
			case 'pending':return '待审核';
			case 'draft':return '草稿';
			default:return '待定';
		}
	}
	
	function get_edit_post($pid){
		return add_query_arg(['pid'=>$pid],home_url('sendpost'));
	}
	
	function get_hostname(){
	    $siteurl=get_option('siteurl');
	    $host=parse_url($siteurl);
	    return $host['host'];
	}