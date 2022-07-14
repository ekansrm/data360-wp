<?php
	get_header();
	$sidemenu=get_menu_array('side');
	
?>
 <div class='container homebody mt15'>
  <?php
		get_template_part('template-parts/common/sidemenu');
	 ?>
	
	 <div class='homebk-ctn'>



		 <!-- 首页列表三 --> 
		<div class='homebk4  homebk'>
		 <!-- 面包屑 --> 
		 <?php echo get_breadcrumb();?>
		 
		 <div class="add-ad pcadd-ad">
				<?php echo wpzt('category-ad');?>
			</div>
		
		<!-- 移动端广告位 --> 
		
		<div class="add-ad madd-ad dis-none">
			<?php echo wpzt('m-category-ad');?>
			</div>
		 
		<div class='homebk4-ctn'>
		<?php
			if(have_posts()){
				while(have_posts()){
					the_post();
					$pid=get_the_ID();
					$categories=get_the_category();
					$cate=$categories[0];
		?>
		<div class='homebk4-item'>
		<div class='homebk4-img'>	
		<a href='<?php the_permalink();?>' title='<?php the_title();?>'><img src='<?php echo get_post_img(155,105);?>' alt='<?php the_title();?>'></a>	</div>
		<div class='homebk4-title'>
		<h3><a href='<?php the_permalink();?>' title='<?php the_title();?>'><?php the_title();?></a></h3>
		<p><?php the_excerpt();?></p>
		<div class='homebk4-date'>
		<a href='<?php echo get_category_link($cate->term_id);?>' title='<?php echo $cate->name;?>'><?php echo $cate->name;?></a>
		<span time="<?php the_time('Y-m-d H:i:s');?>"><i class='iconfont icon-shijian'></i><?php echo timeago(get_the_time('Y-m-d H:i:s'));?></span>
		<span><i class='iconfont icon-yanjing'></i><?php echo get_views($pid);?></span>
		</div>
		</div>
		</div>
		<?php
			}}
				?>
		
	 </div>
	 
	 <div class='w-fylink'>
		 <?php letan_page();?>
	</div>
	 </div>
	 </div>
<!-- 侧边栏 --> 
	 <div class='sidebar mb-hid'>
	 <?php dynamic_sidebar('category_side');?>
		
	 </div>
	 </div>


<?php	
	get_footer();