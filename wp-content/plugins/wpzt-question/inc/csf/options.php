<?php if (!defined('ABSPATH')) {die;} 

$question_option_prefix=WPZT_PQ_OPTION;

CSF::createOptions($question_option_prefix, array(
    'menu_title' => '问答设置',
    'menu_slug'  => 'csf-wpzt-question',
	'menu_type'       => 'menu', // menu, submenu, options, theme, etc.
	'ajax_save'       => true,
));

CSF::createSection($question_option_prefix,array(
	'title'=>'常规设置',
	'name'=>'img_set',
	'fields'=>[
		array(
			'id'=>'send_status',
			'type'=>'switcher',
			'title'=>'问题直接发布',
			'desc'=>'开启为直接发布，否则为审核发布',
			'default'=>true
		),
		array(
			'id'=>'category',
			'type'=>'checkbox',
			'title'=>'选择可发布的话题分类',
			'options'=>'tags',
			'query_args'=>['hide_empty'=>false,'taxonomies'=>'question']
		),
		array(
			'id'=>'filter_content',
			'type'=>'textarea',
			'title'=>'关键词过滤',
			'desc'=>'需要过滤掉的关键词用|分割开'
		),
		array(
			'id'=>'link_nofollow',
			'type'=>'switcher',
			'title'=>'外链nofollow',
			'default'=>true
		),
		array(
			'id'=>'yuanjiao',
			'type'=>'switcher',
			'title'=>'圆角',
			'default'=>true
		),
		array(
			'id'=>'color',
			'type'=>'color',
			'title'=>'主题色',
			'default'=>'#007bff'
		)
	]
));

function wpzt_pq($name){
	$options=get_option( WPZT_PQ_OPTION );
	return empty($options[$name])?'':$options[$name];
}

function filter_content($content){//过滤内容
	$filter=wpzt_pq('filter_content');
	if(empty($filter)){return $content;}
	$filter_list=explode('|',$filter);
	$filter_array=array_combine($filter_list,array_fill(0,count($filter_list),'*'));
	return strtr($content, $filter_array);
}