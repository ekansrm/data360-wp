<?php
	$catid=$mod['index-mod-one-cat'];
	$args['post_per_page']=10;
	if(!empty($catid)){
		$args['cat']=$catid;
	}
	$the_query=get_cache_query($args);
?>
	<div class='homebk3  homebk'>
		<div class='homebk-title'>
		<h2><span><?php echo $mod['title'];?></span></h2>
		<a href='<?php echo get_category_link($catid);?>' target="_blank" title='<?php echo $mod['title'];?>'>更多>></a>
		</div>
		<div class='homebk3-ctn'>
		<ul>
		<?php
			if($the_query->have_posts()){
				while($the_query->have_posts()){
					$the_query->the_post();
		?>
		<li><a href='<?php the_permalink();?>' title='<?php the_title();?>'><?php the_title();?></a></li>
		<?php
				}
				wp_reset_postdata();
			}?>
		
		</ul>
		 </div>
		</div>
