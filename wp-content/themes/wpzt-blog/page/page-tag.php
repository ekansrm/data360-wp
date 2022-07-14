<?php
/*
Template Name: 标签云
*/
get_header();
get_template_part("template-parts/banner/banner-pc-min");
?>
	<div class=" ptb50 userbqy">
	<div class="container w-bqy">
		<div class="w-bqy-header">
		<h3><i class="iconfont icon-dingwei"></i>热门标签</h3>
		</div>
		<div class="w-bqy-body">
			<ul>
			<?php
				$colorlist=[
					['bg'=>'#e6dcff','bc'=>'#C6A9FF'],
					['bg'=>'#ffe1f7','bc'=>'#ffb9fe'],
					['bg'=>'#d6f8f8','bc'=>'#a9bdbd'],
					['bg'=>'#ffffcf','bc'=>'#e1e154'],
					['bg'=>'#ffe4d7','bc'=>'#ffc5a9'],
					['bg'=>'#ceffce','bc'=>'#77db77'],
					['bg'=>'#e9ffd4','bc'=>'#aee479'],
					['bg'=>'#efefef','bc'=>'#ccc'],
					['bg'=>'#e6dcff','bc'=>'#C6A9FF'],
					['bg'=>'#ffe1f7','bc'=>'#ffb9fe'],
					['bg'=>'#d6f8f8','bc'=>'#a9bdbd'],
					['bg'=>'#ffffcf','bc'=>'#e1e154'],
					['bg'=>'#ffe4d7','bc'=>'#ffc5a9'],
					['bg'=>'#ceffce','bc'=>'#77db77'],
					['bg'=>'#e9ffd4','bc'=>'#aee479'],
					['bg'=>'#efefef','bc'=>'#ccc'],
					['bg'=>'#ffe1f7','bc'=>'#ffb9fe'],
					['bg'=>'#d6f8f8','bc'=>'#a9bdbd'],
					['bg'=>'#ffffcf','bc'=>'#e1e154'],
					['bg'=>'#ffe4d7','bc'=>'#ffc5a9'],
					['bg'=>'#ceffce','bc'=>'#77db77'],
				];
				$number=72;
				$page=get_query_var('paged');
				$page=empty($page)?1:$page;
				$offset=($page-1)*$number;
				$count=get_tags('fields=count');
				$countpage=ceil($count/$number);
				$args=['fields'=>'id=>name','orderby'=>'count','order'=>'DESC','update_term_meta_cache'=>false,'number'=>$number,'offset'=>$offset];
				$tags_list = get_tags($args);
				if($tags_list){
					foreach($tags_list as $k=>$v){
						$color=$colorlist[rand(0,19)];
			?>
				<li><a href="<?php echo get_category_link($k);?>" title="<?php echo $v;?>" style="background:<?php echo $color['bg'];?>;border-color:<?php echo $color['bc'];?>;"><?php echo $v;?></a></li>
				<?php
					}//endforeach
				}//endif
				?>
			</ul>
		
			<div class="w-fy">
				<div class="w-fylink">
					<?php wpzt_paginate($countpage,$page);?>
				</div>
			</div>
		
		</div>

	</div>
	</div>

<?php get_footer();?>