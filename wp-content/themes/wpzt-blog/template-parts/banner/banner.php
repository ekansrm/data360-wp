<?php
$banner=wpzt('banner');
if(!empty($banner)){
	$idlist=explode('|',$banner);
	$args=['post__in'=>$idlist];
}else{
	$args=['posts_per_page'=>5];
}
$bannerlist=get_post_array($args);
if(!empty($bannerlist)){
?>
<style>
iframe{width:840px;height:480px;}
</style>
	<div class='banner container'>
	<!-- 板块内容一--菜单 --> 
	<div class='banner-tabnav swiper-container'>
		<ul class="swiper-wrapper"  role="tablist" id="myTab">
			<?php
				foreach($bannerlist as $k=>$v){
			?>
			  <li class="swiper-slide <?php if($k==0){?>active<?php }?>"  role="presentation" >
				  <a title='<?php echo $v['title'];?>' data-href='#banneritem<?php echo $k;?>' >
				  <img src='<?php echo $v['img'];?>' alt='<?php echo $v['title'];?>'>
				  <div class='bntbv-title'>
				  <h3><?php echo $v['title'];?></h3>
				  <div class='title-date'>
				  <span><i class='iconfont icon-shijian'></i><?php echo $v['time']?></span>
				  <span><i class='iconfont icon-redu'></i><?php echo get_views($v['id']);?></span>
				  </div>
				  </div>
				  </a> 
			  </li>
			 <?php
				}
			 ?> 
			  
		</ul>
	<!-- 如果需要导航按钮 -->
		<div class="swiper-button-prev swiper2-button-prev"></div>
		<div class="swiper-button-next swiper2-button-next"></div>
	</div>
	
	<!-- 板块内容一--内容 --> 
		<div id="myTabContent" class='banner-ctn mt25 tab-content'>
		<!-- ~~~~~~~~ --> 
		<?php
			foreach($bannerlist as $k=>$v){
		?>
			<div class='tab-pane <?php if($k==0){?>active<?php }?>' role='tabpanel' id='banneritem<?php echo $k;?>'>
			<div class='banner-item'>
			<div class='banner-l'>
			
		
			 <?php
				$code=user_can_view($v['id'],$v['meta']);
				if($code==100){
					echo_video($v['meta'],$v['img']);
				}else{
					echo_nofree($v['id'],$v['meta'],$code,$v['img'],$v['title']);
				}
			 ?>
			 
			</div>
			<div class='banner-r'>
			<div class='banner-item-top'>
			<i class='iconfont icon-bofang1'></i>播放</div>
			<h3><a href='<?php echo $v['link'];?>' title='<?php echo $v['title'];?>' ><?php echo $v['title'];?></a></h3>
			<p><?php echo $v['excerpt'];?></p>
			<div class='title-date'>
			  <span><i class='iconfont icon-shijian'></i><?php  echo $v['time'];?></span>
			  <span><i class='iconfont icon-redu'></i><?php echo get_views($v['id']);?></span>
			  </div>
			</div>
			</div>
			</div>
			<?php
			}
			?>
			
			
		
		</div>
	</div>
	
<!-- pc端广告位 -->
				<?php
					if(!empty(wpzt('index-ad'))){
				?>
				  <div class="add-ad container">
					<?php echo wpzt('index-ad');?>
				  </div>
				 <?php
					}
				 ?>
				  <!-- 移动端广告位 -->
				<?php
					if(!empty(wpzt('m-index-ad'))){
				?>
				  <div class="add-ad container madd-ad dis-none">
					<?php echo wpzt('m-index-ad');?>
				  </div>
				<?php
					}
				?>
<?php
}