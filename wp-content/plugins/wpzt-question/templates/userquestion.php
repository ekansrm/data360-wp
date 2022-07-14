<?php
	get_header();
	$wpztuid=get_query_var('wpzt_uid');
	$paged=get_query_var('paged');
	$paged=empty($paged)?1:$paged;
	$args=['author'=>$wpztuid,'paged'=>$paged,'post_type'=>'aq'];
	$the_query=new WP_Query($args);
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
	 <a href='<?php echo home_url('userquestion/'.$wpztuid);?>' title='提问' class='active'>提问</a>
	 <a href='<?php echo home_url('useraq/'.$wpztuid);?>' title='回答'>回答</a>
	</div>
	<div class='linkhf-btn'>
	<!----
	<a href='#' title='文章'>返回问答首页</a>--->
	<a href='javascript:;' title='文章' class='jstwbtn'><i class='iconfont icon-info-1'></i>我要提问</a>
	</div>
	</div>
	<ul class='qapage-linkul'>
	<?php
		if($the_query->have_posts()){
			while($the_query->have_posts()){
				$the_query->the_post();
				$pid=get_the_ID();
				global $authordata,$post;
				$authorid=$authordata->ID;
				$terms=get_the_terms($pid,'question');
				$term=$terms[0];
	?>
	<li>
		<a href='<?php echo home_url('userquestion/'.$wpztuid);?>' title='<?php echo $authordata->display_name;?>' class='linkul-img'>
			<img src='<?php echo pq_get_user_avatar($authorid);?>' alt='<?php echo $authordata->display_name;?>'>
		</a>
		<h3><a href='<?php the_permalink();?>' title='<?php the_title();?>'><?php the_title();?></a></h3>
		<div class='qapage-date'>
			<a href='<?php echo get_term_link($term->term_id);?>' title='<?php echo $term->name;?>'><?php echo $term->name;?></a><span time="<?php the_time('Y-m-d H:i:s');?>"><?php echo pq_timeago(get_the_time('Y-m-d H:i:s'));?></span><span>回复数：<?php echo $post->comment_count;?></span>
		</div>
	</li>
		<?php	}
			wp_reset_postdata();
		}?>
	</ul>
	<div class='w-fylink'>
	<!---分页---->
		 <?php letan_pq_page($the_query,$paged);?>
	</div>
	</div>
	</div>
	<div class='qapage-sidebar'>
		<!---侧边栏小工具--->
	<?php dynamic_sidebar('aq_side');?>
	</div>
	</div>
	</div>
	</div>


<?php
get_footer();