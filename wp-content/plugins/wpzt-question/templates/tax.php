<?php
	get_header();
	wp_enqueue_script('wangeditor');
	wp_enqueue_script('letan');
	$queried_object=get_queried_object();
	$paged=get_query_var('paged');
	$paged=empty($paged)?1:$paged;
	$args=['post_type'=>['aq'],'paged'=>$paged,'tax_query'=>[['taxonomy'=>'question','field'=>'term_id','terms'=>$queried_object->term_id]]];
	$the_query=new WP_Query($args);
	$child_terms=get_term_children($queried_object->term_id,'question');
	//$allterms_args=['taxonomy'=>'question','fields'=>'id=>name','hide_empty'=>false];
	//$all_terms=get_terms($allterms_args);
	$all_terms=wpzt_pq('category');
	require_once WPZT_PQ_DIR."/templates/css.php";
?>
<?php echo get_pq_breadcrumb();?>

	<div class='qapagebg'>
	<div class='container qapage'>
	<div class='qapage-top'>
	<div class='qapage-ask'>
	<div class='qapage-askform'>
	<form action="<?php echo home_url();?>">
		<input type='text' name="s" class='form-control'>
		<input type="hidden" name="post_type" value="question"/>
		<button type="submit"><i class='iconfont icon-search'></i></button>
	</form>
	<button type="submit" class='twbtn jstwbtn'>提问</button>
	</div>
	</div>
<?php
	if(is_user_logged_in()){
		$user=wp_get_current_user();
		$uid=$user->ID;
		$comment_count=get_comments(['user_id'=>$uid,'count'=>true,'post_type'=>'aq']);
?>
	<div class='qapage-uc'>
	<div  class='qapage-ucimg'>
	<img src='<?php echo pq_get_user_avatar($uid);?>' alt='头像'>
	<span><?php echo $user->display_name;?></span>
	</div>
	<div class='qapage-uclink'>
	<a href='<?php echo home_url('userpost/'.$uid);?>' title='文章'>文章<span><?php echo count_user_posts($uid,'post',true);?></span></a>
	<a href='<?php echo home_url('userquestion/'.$uid);?>' title='提问'>提问<span><?php echo count_user_posts($uid,'aq',true);?></span></a>
	<a href='<?php echo home_url('useraq/'.$uid);?>' title='回答'>回答<span><?php echo $comment_count;?></span></a>
	</div>
	</div>
<?php
	}else{
?>
	<div class='qapage-uc'>
		<div  class='qapage-ucimg'>
			<img src='<?php echo WPZT_PQ_IMG;?>/avatar.png' alt='头像'>
			<span class='wdl'>未登录,<a href='<?php echo get_pq_login_url();?>' rel="nofollow" title='登录'>点击登录</a>~</span>
		</div>
		<div class='qapage-uclink'>
			<a href='#' rel="nofollow" title='文章'>文章<span>0</span></a>
			<a href='#' rel="nofollow" title='提问'>提问<span>0</span></a>
			<a href='#' rel="nofollow" title='回答'>回答<span>0</span></a>
		</div>
	</div>

<?php }?>

	
	</div>
	<div class='qapage-body'>
	
	<div class='qapage-linkctn'>
	<div class='qapage-link'>
	<div class='qapage-linkfl'>
		<div class='linkhf-a'>
		<a href='<?php echo get_term_link($queried_object->term_id);?>' title='<?php echo $queried_object->name;?>' class='active'><?php echo $queried_object->name;?></a>
			
		<?php
			if(!empty($child_terms)){
				foreach($child_terms as $v){
					$term=get_term($v);
		?>
		<a href='<?php echo get_term_link($v);?>' title='<?php echo $term->name;?>'><?php echo $term->name;?></a>
			<?php }
			}?>
	</div>
	</div>
	<ul class='qapage-linkul'>
	<?php
		if($the_query->have_posts()){
			while($the_query->have_posts()){
				$the_query->the_post();
				$pid=get_the_ID();
				$terms=get_the_terms($pid,'question');
				$term=$terms[0];
				global $authordata; 
	?>
		<li>
		<a href='<?php echo home_url('userquestion/'.$authordata->ID);?>' title='<?php echo $authordata->display_name;?>' class='linkul-img'><img src='<?php echo pq_get_user_avatar($authordata->ID);?>' alt='<?php $authordata->display_name;?>'/></a>
		<h3><a href='<?php the_permalink();?>' title='<?php the_title();?>'><?php the_title();?></a></h3>
		<div class='qapage-date'><a href='<?php echo get_term_link($term->term_id,'question');?>' title='<?php echo $term->name;?>'><?php echo $term->name;?></a><span time="<?php the_time('Y-m-d H:i:s');?>"><?php echo pq_timeago(get_the_time('Y-m-d H:i:s'));?></span><span>浏览量：<?php echo pq_get_views($pid);?></span><span>回复数：<?php echo get_comments_number();?><span></div>
		</li>
	<?php
			}
			wp_reset_postdata();
		}?>
	</ul>
	
	<div class='w-fylink'>
		<?php letan_pq_page($the_query);?>
	</div>
	
	</div>
	</div>
	<div class='qapage-sidebar'>
	<!---侧边栏小工具---->
	<?php dynamic_sidebar('aq_side');?>
	
	</div>
	</div>
	</div>
	</div>
	
	
	<!-- 提问 -->
	<?php
		require_once WPZT_PQ_DIR.'/templates/modal.php';
	?>

<?php
	wp_enqueue_script('wdcommon');
	get_footer();