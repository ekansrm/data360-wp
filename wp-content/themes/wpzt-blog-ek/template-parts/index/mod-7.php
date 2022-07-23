<?php
	$paged=empty(get_query_var('paged'))?1:get_query_var('paged');	
	$args=['paged'=>$paged];
	$the_query=get_cache_query($args);
?>
	<div class='homebk4  homebk'>
		<div class='homebk-title'>
		<h2><span><?php echo $mod['title'];?></span></h2>
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
	
		<div class='w-fylink'>
		 <?php if($mod['showpage']){
			 letan_page($the_query,$paged);
			 }elseif($the_query->max_num_pages>1){?>
	<div class="load-more-wrap">
		<a class="load-more j-load-more" data-page="<?php echo wpzt_Enc($paged);?>" href="javascript:;">点击查看更多</a>
	</div>		 
<?php } ?>
		</div>
	 </div>