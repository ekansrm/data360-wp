<?php 
add_js("footer");
$footermenu=get_menu_array('footer');
?>
	<footer class='footer'>
	<?php
		if(!empty($footermenu)){
	?>
		<div class='footer-link'>
		<ul>
		<?php
			foreach($footermenu as $v){
		?>
		<li><a href='<?php echo $v['link'];?>' title='<?php echo $v['name'];?>' target="<?php echo $v['target'];?>"><?php echo $v['name'];?></a></li>
		<?php }?>
		</ul>
		</div>
	<?php
		}
	?>
	<?php
			if(is_home()&&wpzt('foot_link')){	//首页显示和开启友情链接
				$foot_link_cat= wpzt('foot_link_cat');
				$args=['category'=>$foot_link_cat];
				$links=get_cache_bookmarks($args);
				if(!empty($links)){
	?>
		<div class='friend-link'>
		<span>友情链接：</span>
		<?php
			foreach($links as $v){
		?>
		<a href="<?php echo $v->link_url;?>" target="<?php echo $v->link_target;?>" title="<?php echo $v->link_name;?>"><?php echo $v->link_name;?></a>
		<?php
			}
			?>
		</div>
	<?php
			}
			}
	?>			
		
		<p>
		<?php echo wpzt('footer_copyright');?>
		<?php if(!empty(wpzt('footer_icp'))){?><a href="https://beian.miit.gov.cn/" rel="nofollow" target="_blank"><?php echo wpzt('footer_icp');?></a><?php }?>
		<?php  if(!empty(wpzt('footer_gaba'))){?>&nbsp;&nbsp;<a href="<?php echo wpzt('footer_gaba_link');?>" target="_blank" rel="nofollow" title="公安备案"><?php echo wpzt('footer_gaba');?></a><?php }?>
		</p>
	</footer>	 
	 <div class="QZ-up" style="display:block;"></div>
	 
	 
	 

<?php wp_footer();?>

<div style="display: none !important;"><?php echo wpzt('foot_code');?></div>
</div>
</body>
</html>