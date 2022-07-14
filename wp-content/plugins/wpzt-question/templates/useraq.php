<?php
get_header();
$wpztuid=get_query_var('wpzt_uid');
$paged=get_query_var('paged');
$paged=empty($paged)?1:$paged;
$args=['post_type'=>'aq','number'=>10,'paged'=>$paged,'user_id'=>$wpztuid,'status'=>'approve'];
$comments=get_comments($args);
$countargs=['post_type'=>'aq','user_id'=>$wpztuid,'status'=>'approve','count'=>true];
$comment_count=get_comments($countargs);
$pagecount=intval($comment_count)/10;
$pagecount=ceil($pagecount);
$uid=get_current_user_id();
wp_enqueue_script('wangeditor');
wp_enqueue_script('letan');
?>
<div class='qapagebg'>
<div class='container qapage'>
	<div class='qapage-body'>	
	<div class='qapage-linkctn'>
	<?php
		require_once WPZT_PQ_DIR."/templates/authorinfo.php";
		require_once WPZT_PQ_DIR."/templates/css.php";
	?>
	
	<div class='qapage-link'>
	<div class='qapage-linkfl'>
	<div class='linkhf-a'>
	 <a href='<?php echo home_url('userpost/'.$wpztuid);?>' title='文章'>文章</a>	
	 <a href='<?php echo home_url('userquestion/'.$wpztuid);?>' title='提问' >提问</a>
	 <a href='<?php echo home_url('useraq/'.$wpztuid);?>' title='回答' class='active'>回答</a>
	 </div>
	<div class='linkhf-btn'>
	<!----
	<a href='#' title='文章'>返回问答首页</a>---->
	<a href='javascript:;' title='文章' class='jstwbtn'><i class='iconfont icon-info-1'></i>我要提问</a>
	</div>
	</div>
	<ul class='qapage-aqul'>
		<?php 
		if(!empty($comments)){
			foreach($comments as $v){
				$pid=$v->comment_post_ID;
				$post=get_post($pid);
		?>
	
		<li>
		<p><i class='iconfont icon-bubbles-chat'></i><?php echo pq_timeago($v->comment_date);?>·回答<a href='<?php echo get_permalink($pid);?>' title='<?php echo $post->post_title;?>' class='aqtitle'><?php echo $post->post_title;?></a>
		<?php
			if($uid==$wpztuid){
		?>
		<a  title='删除' onclick="del_comment(<?php echo $v->comment_ID;?>)" class='shanchu'>删除</a>
		<?php }?>
		
		</p>
		<p><a href='<?php echo get_permalink($pid);?>#comment-<?php echo $v->comment_ID?>' title='回答'><?php echo $v->comment_content;?></a></p>
		</li>
		<?php
			}
		}?>
	
	
	</ul>
	
	
	
	<div class='w-fylink'>
		 <?php wpzt_pq_paginate($pagecount,$paged);?>
		</div>
	</div>
	</div>
	<div class='qapage-sidebar'>
	<!---侧边栏---->
	<?php dynamic_sidebar('aq_side');?>
	</div>
	</div>
	</div>
	</div>


<?php
get_footer();