<?php
if(!function_exists('pq_get_user_avatar')){
	function pq_get_user_avatar($uid){
		$useravatar=get_user_meta($uid,'avatar',true);
		if($useravatar){
			return $useravatar;
		}else{
			return WPZT_PQ_IMG.'/avatar.png';
		}
	}
}

if(!function_exists('pq_shortnum')){
	function pq_shortnum($number){
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
}

if(!function_exists('pq_get_views')){
	function pq_get_views($pid){
		$views=get_post_meta($pid,'views',true);
		$views=empty($views)?0:$views;
		return pq_shortnum($views);
	}
}
if(!function_exists('pq_timeago')){
	function pq_timeago($ptime) {		//文档时间显示
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
}

	function wpzt_pq_paginate($count,$current=1,$p=2){
		if($count==1){return;}//仅有一页不用分页
		if($current>1){pq_echo_page($current-1,'上一页','<i class="iconfont icon-shangyiye"></i>');}
		if($current>$p+1){pq_echo_page(1,'首页');}
		if($current>$p+2){echo "<a class='page-numbers' href='javascript:void(0);'>...</a>";}
		for($i=$current-$p;$i<=$current+$p;$i++){
			if($i>0&&$i<=$count){
				if($i==$current){
					echo "<a class='active' href='javascript:void(0);'>{$i}</a>";
				}else{
					pq_echo_page($i);
				}
			}	
		}
		if($current<$count-$p-1){echo "<a class='page-numbers' href='javascript:void(0);'>...</a>";}
		if($current<$count-$p){pq_echo_page($count,'尾页');}
		if($current<$count){pq_echo_page($current+1,'下一页','<i class="iconfont icon-xiayiye"></i>');}
	}
	
	function pq_echo_page($i,$title=null,$icon=""){
		$title=$title?:"第{$i}页";
		$icon=$icon?:$i;
		$link=get_pagenum_link($i);
		echo "<a title='{$title}' href='{$link}'>{$icon}</a>";
	}
	
	function letan_pq_page($query=null,$current=0,$p=2){	//分页
		if(empty($query)){
			global $wp_query;
			$query=$wp_query;
		}
		$count=$query->max_num_pages;
		if(empty($current)){
			$current=get_query_var('paged')?:1;
		}
		wpzt_pq_paginate($count,$current,$p);
	}
	
	function get_pq_breadcrumb(){	//面包屑	
		$str='<ul class="breadcrumb qapage-breadcrumb container">
				<span><i class="iconfont icon-dingwei"></i>当前位置：</span>';
		$str.='<li><a href="'.home_url().'" title="首页">首页</a></li>&gt;';
			if(is_tax('question')){
				$term=get_queried_object();
				$catstr='<li>'.$term->name.'</li>';
				while($term->parent!=0){
					$term=get_term($term->parent);
					$catstr="<li><a href='".get_term_link($term->term_id)."' title='".$term->name."'>".$term->name."</a></li>&gt;".$catstr;
				}
				$str.=$catstr;
			}elseif(is_single()){
				$pid=get_the_ID();
				$terms=get_the_terms($pid,'question');
				$term=$terms[0];
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

	function get_pq_login_url(){
		if(is_ssl()){
			$scheme="https";
		}else{
			$scheme="http";
		}
		$current_url=$scheme.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$login_url=home_url('login');
		return add_query_arg(['redirect_to'=>$current_url],$login_url);
	}

	function wpzt_pq_get_post_img(){
			global $post;
		if( has_post_thumbnail() ){
			$timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
			$src = $timthumb_src[0];
		}else{
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches ,PREG_SET_ORDER);
			$cnt = count( $matches );
			if($cnt>0){
				$src = $matches[0][1];
			}else{ // thumb	
				$src = WPZT_PQ_IMG.'/no-image.png';
			}
		}
		return $src;
	}

	function pq_timeago($ptime) {		//文档时间显示
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
