<?php
	$uid=get_query_var('wpzt_uid');
	if(empty(intval($uid))){
		exit('404');
	}
	$user=get_user_by("ID",$uid);
	$comment_count=get_comments(['user_id'=>$uid,'count'=>true,'post_type'=>'aq']);
	require_once WPZT_PQ_DIR.'/templates/modal.php';
	wp_enqueue_script('wdcommon');
	
?>
		<div class='qapage-article qapage-author'>
			<div class='qapage-ucimg'>
				<img src='<?php echo pq_get_user_avatar($uid);?>' alt='头像'/>
			</div>
			<div class='qapage-uclink'>
				<span><?php echo $user->display_name;?></span>
				<div>
					<a href='<?php echo home_url('userpost/'.$uid);?>' title='文章'>文章<span><?php echo count_user_posts($uid,'post',true);?></span></a>
					<a href='<?php echo home_url('userquestion/'.$uid);?>' title='提问'>提问<span><?php echo count_user_posts($uid,'aq',true);?></span></a>
					<a href='<?php echo home_url('useraq/'.$uid);?>' title='回答'>回答<span><?php echo $comment_count;?></span></a>
				</div>
			</div>
		</div>
		