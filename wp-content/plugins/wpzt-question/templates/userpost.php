<?php
	get_header();
	$wpztuid=get_query_var('wpzt_uid');
	$paged=get_query_var('paged');
	$paged=empty($paged)?1:$paged;
	$args=['author'=>$wpztuid,'paged'=>$paged,'post_type'=>'post'];
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
	 <a href='<?php echo home_url('userpost/'.$wpztuid);?>' title='文章' class='active'>文章</a>	
	 <a href='<?php echo home_url('userquestion/'.$wpztuid);?>' title='提问'>提问</a>
	 <a href='<?php echo home_url('useraq/'.$wpztuid);?>' title='回答'>回答</a>
	</div>
	<div class='linkhf-btn'>
	<!---
	<a href='#' title='文章'>返回问答首页</a>--->
	<a href='javascript:;' title='文章' class='jstwbtn'><i class='iconfont icon-info-1'></i>我要提问</a>
	</div>
	</div>
	
	<ul class='qapage-linkul qapage-linkulwz'>
	<?php
		if($the_query->have_posts()){
			while($the_query->have_posts()){
				$the_query->the_post();
				$categories=get_the_category();
				$cate=$categories[0];
				$pid=get_the_ID();
	?>
		<li>
			<a href='<?php the_permalink();?>' title='<?php the_title();?>' class='linkul-img'><img src='<?php echo wpzt_pq_get_post_img();?>' alt='<?php the_title();?>'></a>
			<h3><a href='<?php the_permalink();?>' title='<?php the_title();?>'><?php the_title();?></a></h3>
			<div class='qapage-date'>
				<a href='<?php echo get_category_link($cate->term_id);?>' title='<?php echo $cate->name;?>'><?php echo $cate->name;?></a>
				<span time="<?php the_time('Y-m-d H:i:s');?>"><?php echo pq_timeago(get_the_time('Y-m-d H:i:s'));?></span><span>浏览：<?php echo pq_get_views($pid);?></span>
			</div>
		</li>
	<?php
			}
			wp_reset_postdata();
		}
	?>
	</ul>
		<div class='w-fylink'>	 
		 <?php letan_pq_page($the_query,$paged);?>
		</div>
	</div>
	</div>
	<div class='qapage-sidebar'>
	<!---侧边栏--->	
	<?php dynamic_sidebar('aq_side');?>
	
	</div>
	</div>
	</div>
	</div>
<?php
get_footer();