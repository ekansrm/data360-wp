<?php
	get_header();
	while(have_posts()){
		the_post();
		global $authordata;
		$pid=get_the_ID();
		$terms=get_the_terms($pid,'question');
		$term=$terms[0];
		require_once WPZT_PQ_DIR."/templates/css.php";
?>
<?php echo get_pq_breadcrumb();?>
	<div class=' qapagebg'>
	<div class='container qapage'>
		<div class='qapage-body'>	
			<div class='qapage-linkctn'>
			<div class='qapage-article'>
			<div class='article-title'>
			<h1><?php the_title();?></h1>
				<div class='qapage-date'>
					<a href='<?php echo home_url('userquestion/'.$authordata->ID);?>' title='<?php echo $authordata->display_name;?>'><?php echo $authordata->display_name;?></a>
					<span><a href='<?php echo get_term_link($term->term_id);?>' title='<?php echo $term->name;?>'><?php echo $term->name;?></a></span>
					<span><?php echo pq_timeago(get_the_time('Y-m-d H:i:s'));?></span><span>浏览量：<?php echo pq_get_views($pid);?></span>
				</div>
			</div>
			<?php
				if(!empty(get_the_content())){
			?>
			<div class='article-ctn'>
			<?php the_content();?>
			</div>
			<?php }?>
			</div>
			
			<div class='qapage-link'>
				<!---回答---->
			<?php comments_template(WPZT_PQ_DIR."/templates/comments.php");?>
			
			</div>
			</div>
			<div class='qapage-sidebar'>
			<!----侧边栏---->	
				<?php dynamic_sidebar('aq_side');?>
			</div>
		</div>
	</div>
	</div>
	
	
	
		
<!-- 模态框-提问表单 -->
<?php
	require_once WPZT_PQ_DIR.'/templates/modal.php';
?>
<?php
	}
	wp_enqueue_script('wdcommon');
	wp_enqueue_script('comment');
get_footer();