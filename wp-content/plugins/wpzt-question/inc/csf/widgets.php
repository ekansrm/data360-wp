<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

CSF::createWidget( 'wpzt_aq_list', array(
	'title'=>'问答列表',
	'description'=>'展示问答列表',
	'fields'=>[
		array(
			'id'=>'title',
			'type'=>'text',
			'title'=>'标题'
		),
		array(
			'id'=>'cat',
			'type'=>'checkbox',
			'title'=>'选择显示问答的分类',
			'options'=>'tags',
			'query_args'=>['hide_empty'=>false,'taxonomies'=>'question'],
			'desc'=>'不选择分类，默认为全部分类'
		)
	]
));

function wpzt_aq_list($args,$instance){
	$title=$instance['title'];
	$cats=$instance['cat'];
	if(empty($title)){
		$title='问答列表';
	}
	if(empty($cats)){
		$query_args=['posts_per_page'=>6,'post_type'=>'aq'];
	}else{
		$query_args=['posts_per_page'=>6,'post_type'=>'aq','tax_query'=>[['taxonomy'=>'question','field'=>'id','terms'=>$cats,'operator' => 'IN']]];
	}
	$the_query=new WP_Query($query_args);
	echo "<div class='qapage-sidebar-1'>
			<div class='qapage-sidebar-title'>
			<h3>{$title}</h3>
			</div>
			<div class='qapage-sidebarul'>
				<ul>";
		if($the_query->have_posts()){
			while($the_query->have_posts()){
				$the_query->the_post();
				$link=get_the_permalink();
				$title=get_the_title();
				$time=get_the_time('Y-m-d H:i:s');
				$timeago=pq_timeago($time);
				$commentnumber=get_comments_number();
				echo "<li>
				<h4><a href='{$link}' title='{$title}'>{$title}</a></h4>
				<div class='qapage-date'><span title='{$title}'>{$timeago}</span><span>回复数：{$commentnumber}</span></div>
				</li>";
			}
			wp_reset_postdata();
		}
	echo "</ul></div></div>";
}