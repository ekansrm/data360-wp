<?php 
get_header();
$cate=get_queried_object();
if($cate->taxonomy=='special'){	
		get_template_part('template-parts/special/special-list');
}
get_footer();