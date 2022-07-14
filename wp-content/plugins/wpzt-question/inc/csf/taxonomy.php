<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.
	$prefix_tax_question = WPZT_PQ_QUESTION_TAX;
	
	CSF::createTaxonomyOptions($prefix_tax_question, array(
		'id'       => 'question_options',
		'taxonomy' => 'question', // category, post_tag or your custom taxonomy name
		'data_type' => 'serialize', 
	));
	
	CSF::createSection($prefix_tax_question, array(
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