<?php
	$catid=$mod['index-mod-one-cat'];
	$args['posts_per_page']=8;
	if(!empty($catid)){
		$args['cat']=$catid;
	}
	$the_query=get_cache_query($args);
?>

<div class='homebk2 homebk'>
		<div class='homebk-title'>
		<h2><span><?php echo $mod['title'];?></span></h2>
		<a href='<?php echo get_category_link($catid);?>' target="_blank" title='<?php echo $mod['title'];?>'>更多>></a>
		</div>
		<div class='homebk2-ctn'>
		<?php
			if($the_query->have_posts()){
				while($the_query->have_posts()){
					$the_query->the_post();
		?>	
		<div class='homebk2-item'>
		<a href='<?php the_permalink();?>' title='<?php the_title();?>'>
		<div class='homebk2-img'>
		<img src='<?php echo get_post_img(160,100);?>' alt='<?php the_title();?>'>
		</div>
		<h3><?php the_title();?></h3>
		</a>
		</div>
		<?php
				}
				wp_reset_postdata();
			}
		?>		
		 </div>
</div>