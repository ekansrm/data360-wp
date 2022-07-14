<?php
	$banner=$mod['banner'];
	if(!empty($banner)){
?>
<div class='swiper-container swiper-banner mb20'>
			<div class="swiper-wrapper">
				<?php
					foreach($banner as $v){
				?>
				<div class="swiper-slide"><a href='<?php echo $v['link'];?>' title='<?php echo $v['title'];?>' class='active'><img src='<?php echo $v['img']['url'];?>' alt='<?php echo $v['title'];?>'></a></div>
				<?php
					}?>
			</div>
				<!-- 如果需要分页器 -->
				<div class="swiper-pagination"></div>
				
				<!-- 如果需要导航按钮 -->
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
		</div>
<?php
	}
?>