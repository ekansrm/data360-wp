<?php
	$catid=$mod['index-mod-one-cat'];
	$args['posts_per_page']=8;
	if(!empty($catid)){
		$args['cat']=$catid;
	}
	$the_query=get_cache_query($args);
?>
	<div class='homebk4  homebk'>
		<div class='homebk-title'>
		<h2><span><?php echo $mod['title'];?></span></h2>
		<a href='<?php echo get_category_link($catid);?>' target="_blank" title='<?php echo $mod['title'];?>'>更多>></a>
		</div>
		<div class='homebk4-ctn'>
		<?php
			if($the_query->have_posts()){
				while($the_query->have_posts()){
					$the_query->the_post();
					$pid=get_the_ID();
		?>
		
		
		<div class='homebk4-item'>
		<div class='homebk4-img'>	
		<a href='<?php the_permalink();?>' title='<?php the_title();?>'><img src='<?php echo get_post_img(155,105);?>' alt='<?php the_title();?>'></a>	</div>
		<div class='homebk4-title'>
		<h3><a href='<?php the_permalink();?>' title='<?php the_title();?>'><?php the_title();?></a></h3>
		<p><?php the_excerpt();?></p>
		<div class='homebk4-date'>
		<span time="<?php the_time('Y-m-d H:i:s');?>"><i class='iconfont icon-shijian'></i><?php echo timeago(get_the_time('Y-m-d H:i:s'));?></span>
		<span><i class='iconfont icon-yanjing'></i><?php echo get_views($pid);?></span>
		</div>
		</div>
		</div>
		<?php
				}
				wp_reset_postdata();
			}?>
	 </div>
	 </div>