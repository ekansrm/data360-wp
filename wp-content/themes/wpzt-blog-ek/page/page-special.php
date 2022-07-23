<?php
/*
Template Name: 专题列表
*/
get_header();
$args=['hide_empty'=> true];
$terms=get_terms(['special'],$args);
?>
<div class="specialbg">
	<div class="special container">
	<?php while(have_posts()){the_post();?>
	<h2><?php the_title();?></h2>
	<?php the_content();?>
	<?php }?>
	<div class="specialflex">
	
	 <?php
		if(!empty($terms)){
			foreach($terms as $v){
				$meta=get_term_meta($v->term_id,CS_TAXONOMT_SPECIAL,true);
	 ?>	 
		<div class="special-listitem">
			<div class="spclst-top">
				<a href="<?php echo get_term_link($v->term_id);?>" title="<?php echo $v->name;?>" class="spclst-top-img">
					<img src="<?php echo $meta['cover']['url'];?>" alt="<?php echo $v->name;?>">
				</a>
				<div class="spclst-top-r">
					<h3><a href="<?php echo get_term_link($v->term_id);?>" title="<?php echo $v->name;?>"><?php echo $v->name;?></a></h3>
					<p><?php echo $v->description;?></p>
					<a href="<?php echo get_term_link($v->term_id);?>" title="<?php echo $v->name;?>" class="goinspecial">进入专题</a>
				</div>

			</div>
			<ul class="spclst-bottom">
				<?php
					$post_arg=['ignore_sticky_posts'=>false,'posts_per_page'=>5,'tax_query'=>[['taxonomy'=>'special','field'=>'id','terms'=>$v->term_id]] ];
					$postlist=get_post_array($post_arg);
					if($postlist){
						foreach($postlist as $j){
				?>
				<li><a href="<?php echo $j['link']?>" title="<?php echo $j['title']?>"><?php echo $j['title']?></a></li>
				<?php
						}//endforeach
					}//endif
				?>
			</ul>
		
		</div>
	<?php
			}//endforeach
		}//endif
	?>
	
	</div>
	</div>
  </div>
<?php	
get_footer();