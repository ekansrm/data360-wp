<?php
	get_header();
	
	add_js('jquery.qrcode.min');
	add_js('jquery.validate');
	add_js('common');
		while(have_posts()){
			the_post();
			$pid=get_the_ID();
			
			
			$category=get_the_category($pid);
			$two=$category[0];
			$url=get_permalink();
			$title=get_the_title();
			$excerpt=get_the_excerpt();
			$img=get_post_img(200,200);
	?>
	 <div class='container homebody mt15 pagebody'>
	 <div class='homebk-ctn'>
		 <!-- 内容详情 --> 
		<div class='homebk4  homebk'>
		 <!-- 面包屑 --> 
		 <?php echo get_breadcrumb();?>
		<div class='homebk4-ctn'>
		<div class='newspage-body'>
  <!-- 文章内容 -->
		  <div class="w-wznrctn">
			 <div class="w-wznr-header">
                  <h1><?php the_title();?></h1>
                  <div class="w-wzhd-icon">
				  <?php
					$uid=get_current_user_id();
					global $authordata;
					if(!empty($uid)&&!empty($authordata)){
						if($uid==$authordata->ID||current_user_can( 'manage_options' )){
							edit_post_link('<span><i class="iconfont icon-edit"></i>[编辑文章]</span>');
						}else{
							$url=get_author_posts_url($authordata->ID);
							$authorname=$authordata->display_name;
							echo "<span><a href='{$url}' rel='author' title='{$authorname}'><i class='iconfont icon-yonghu'></i> {$authorname}</a></span>";
						}
					}else{
							$url=get_author_posts_url($authordata->ID);
							$authorname=$authordata->display_name;
							echo "<span><a href='{$url}' rel='author' title='{$authorname}'><i class='iconfont icon-yonghu'></i> {$authorname}</a></span>";
					}
				  ?>
                    <span>
                      <a href="<?php echo get_category_link($two->term_id);?>">
                        <i class="iconfont icon-wenjianjia"></i><?php echo $two->name;?></a>
                    </span>
                    <span>
                      <i class="iconfont icon-yanjing"></i>(<?php echo get_views($pid);?>)</span>
                    <span title="<?php the_time('Y-m-d H:i:s');?>">
                      <i class="iconfont icon-shijian"></i><?php echo timeago(get_the_time('Y-m-d H:i:s'));?></span>
                  </div>
                </div>
				
				 <!-- pc端广告位 --> 
		<div class="add-ad pcadd-ad">
			<?php echo wpzt('content-ad');?>
			</div>
		<!-- 移动端广告位 --> 
			<div class="add-ad madd-ad dis-none">
			<?php echo wpzt('m-content-ad');?>
			</div>
			
			  <div class="w-wznr-body">
                 <?php the_content();?>
				 <?php wp_link_pages('before=<div id="content-links" class="w-fylink">&after=</div>&nextpagelink=下一页&previouspagelink=上一页'); ?>
			   </div>
			  
			</div>
			<!-- 标签 --> 
			  <div class="w-wznr-footer">
			  <div class='single-tagctn'>
					<?php 
						$tags=get_the_tags($pid);
						if(!empty($tags)){
					?>	
                  <div class="single-tag">
                    <i class="iconfont icon-biaoqian"></i>
                    <?php the_tags();?></div>
						<?php }?>
				</div>	
                <div class='pbvideo-share'>
					<div class='pbvideo-app'>
					<a href='#' title='微信分享' rel="nofollow" class='weixin position-r'><i class='iconfont icon-weixin'></i>
						<div class='share-ewm'>
						<div id="shareqr"></div>
						<p>扫一扫分享到微信</p>
						</div>	
						</a>
						<a href='<?php echo \wpzt\Share::get_qq_url($url,$title,$img,$excerpt);?>' title='QQ分享' rel="nofollow" class='qq'><i class='iconfont icon-qq'></i></a>
						<a href='<?php echo \wpzt\Share::get_qqz_url($url,$title,$img,$excerpt); ?>' title='QQ空间分享' rel="nofollow" class='qqkongjian'><i class='iconfont icon-qqkongjian'></i></a>
						<a href='<?php echo \wpzt\Share::get_weibo_url($url,$title,$img); ?>' title='微博分享' rel="nofollow" class='weibo'><i class='iconfont icon-weibo'></i></a> 
						
					</div>	
				</div>
                </div>
				
				
				
				<!-- 前后文章切换 -->
			  <?php
					
					$categoryIDS = array();
					foreach ($category as $v) {
						array_push($categoryIDS, $v->term_id);
					}
					$categorylist=$categoryIDS;//相关文章用到的
					//$categoryIDS = implode(",", $categoryIDS);
			  ?>
			  <?php
					$prepost=get_previous_post(true);
					$nextpost=get_next_post(true);
			  ?>
              <div class="single-turepage">
                <div class="single-turepage-prve">
               
					
					<?php if(!empty($prepost)){?>
					<p class='turepage-p'><i class="iconfont icon-double-arrow-left- mb-hid"></i>上一篇</p><p> <a href="<?php echo get_permalink($prepost->ID);?>" title="<?php echo $prepost->post_title;?>"  class='turepage-a'><?php echo $prepost->post_title;?></a> </p>
					<?php }else{?>
					<p class='turepage-p'><i class='iconfont icon-double-arrow-left- mb-hid'></i>上一篇</p><p>已是最后文章</p>
					<?php }?>


			   </div>
                <div class="single-turepage-next">
               
					
					<?php if(!empty($nextpost)){?>
					<p class='turepage-p'><i class="iconfont icon-double-arrow-right- mb-hid"></i>下一篇</p><p> <a href="<?php echo get_permalink($nextpost->ID);?>" title="<?php echo $nextpost->post_title;?>" class='turepage-a'><?php echo $nextpost->post_title;?></a></p>
					<?php }else{?>
					<p class='turepage-p'><i class='iconfont icon-double-arrow-right- mb-hid'></i>下一篇</p><p>已是最新文章</p>
					<?php }?>

			  </div>
              </div>
			   <!-- pc端广告位 --> 
		<div class="add-ad pcadd-ad">
			<?php echo wpzt('content-ad-b');?>
			</div>
		<!-- 移动端广告位 --> 
			<div class="add-ad madd-ad dis-none">
			<?php echo wpzt('m-content-ad-b');?>
			</div>
			<!---评论表单---->
			  <?php comments_template();?>
				
              
		  </div>
		  
		</div>
		</div>
		
	
	 <!-- 相关推荐 --> 
		<div class='homebk2 homebk'>
		<div class='homebk-title'>
		<h2><span>相关推荐</span></h2>
		</div>
		<div class='homebk2-ctn'>
			<div class="w-tjyd-body">
					<div class="w-tjydby w-tjydby-l">
					<?php
						$xg_arg=['category__in'=>$categorylist,'posts_per_page'=>4, 'ignore_sticky_posts' => true,'post__not_in'=>[$pid]];
						$xg_query=get_cache_query($xg_arg);
						if($xg_query->have_posts()){
							while($xg_query->have_posts()){
								$xg_query->the_post();
					?>
						<div class="w-tjydby-limg">
							<a href="<?php the_permalink();?>" title="<?php the_title();?>">
								<img src="<?php echo get_post_img();?>" title="<?php the_title();?>" />
								<h4><?php the_title();?></h4>
							</a>
						</div>
					<?php
							}
							wp_reset_postdata();
						}
					?>
					</div>
					<div class="w-tjydby w-tjydby-r">
						<ul>
						<?php
							$xg_arg1=['category__in'=>$categorylist,'posts_per_page'=>8, 'ignore_sticky_posts' => true,'offset'=>4,'post__not_in'=>[$pid]];
							$xg_query1=get_cache_query($xg_arg1);
							if($xg_query1->have_posts()){
								while($xg_query1->have_posts()){
									$xg_query1->the_post();
						?>
							<li><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></li>
						<?php
								}//endwhile
								wp_reset_postdata();
							}//endif
						?>
						</ul>
					</div>
				</div>
		
	
		 </div>
		</div>
 </div>
 <!-- 侧边栏 -->
        <div class="sidebar mb-hid">
         <?php dynamic_sidebar('content_side')?>	
		</div>
      </div>


	<?php 
		}
		get_footer();
