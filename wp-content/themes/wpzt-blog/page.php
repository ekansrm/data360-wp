<?php
 get_header();
 if(have_posts()){
	 while(have_posts()){
		 the_post();
 ?>
 <div class='pagectn container'>
	<!-- 单页面内容 --> 
	<div class='textpage w-wznrctn'>	
	<div class='w-wznr-header'>	
		<h1><?php the_title();?></h1>
		</div>
		<div class='textpage-ctn w-wznr-body'>
		<?php the_content();?>
		</div>
	</div>
</div>
 
 
<?php
	 }//endwhile
 }//endif
 get_footer();