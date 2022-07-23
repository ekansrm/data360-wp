<?php
 get_header();
 add_js('index');

 ?>
 
 <div class='container homebody mt15'>
	<!-----左侧边栏菜单------->
	<?php get_template_part('template-parts/common/sidemenu');?>
	<!---首页板块---->
	 <div class='homebk-ctn'>
		<?php
	//	 if(!begin_page_cache()){
			$mods=wpzt('home_mod');
			if(!empty($mods)){
				foreach($mods as $mod){
					switch($mod['index-mod']){
						case '1':require locate_template('template-parts/index/banner.php');break;
						case '2':require locate_template('template-parts/index/mod-2.php');break;
						case '3':require locate_template('template-parts/index/mod-3.php');break;
						case '4':require locate_template('template-parts/index/mod-4.php');break;
						case '5':require locate_template('template-parts/index/mod-5.php');break;
						case '6':require locate_template('template-parts/index/mod-ad.php');break;
						case '7':require locate_template('template-parts/index/mod-7.php');break;
					}
				}
			}
	//	 }
   //    end_page_cache();
		?>
		
	 </div>
<!-- 侧边栏 --> 
	 <div class='sidebar mb-hid'>
		<?php
			dynamic_sidebar('home_side');
			?>
		
	 </div>
	 </div>

<?php

 get_footer();
 if(wpzt('home-cache')){
    end_page_cache();
 }