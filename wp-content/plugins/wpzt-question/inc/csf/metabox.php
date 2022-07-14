<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

	$prefix_question_opts = WPZT_PQ_QUESTION;
	CSF::createMetabox($prefix_question_opts, array(
		'title'     => '设置',
		'post_type' => 'question',
		'data_type' => 'serialize',
		'priority'  => 'high',
	));
	
	CSF::createSection($prefix_question_opts,array(
	'title'=>'SEO设置',
	'fields'=>[
		array(
                    'id'    => 'seo_custom_title',
                    'type'  => 'text',
                    'title' => '自定义标题',
                    'after'   => '<div class="cs-text-muted">留空则调用默认全局SEO设置</div>'
                ),
                array(
                    'id'    => 'seo_custom_keywords',
                    'type'  => 'text',
                    'title' => '自定义关键词',
                    'after'   => '<div class="cs-text-muted">留空则调用默认全局SEO设置</div>'
                ),
                array(
                    'id'    => 'seo_custom_desc',
                    'type'  => 'textarea',
                    'title' => '自定义描述',
                    'after'   => '<div class="cs-text-muted">留空则调用默认全局SEO设置</div>'
                ),
		]
	));
	