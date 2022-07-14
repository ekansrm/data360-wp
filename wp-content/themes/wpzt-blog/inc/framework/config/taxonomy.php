<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix_category = CS_TAXONOMY_OPTION;
$prefix_special=CS_TAXONOMT_SPECIAL;




CSF::createTaxonomyOptions($prefix_category, array(
	'id'       => 'category_options',
	'taxonomy' => 'category', // category, post_tag or your custom taxonomy name
    'data_type' => 'serialize', 
));

CSF::createTaxonomyOptions($prefix_special,array(
	'id'	=>'special_options',
	'taxonomy'=>'special',
	'data_type'=>'serialize'
));



CSF::createSection($prefix_category, array(
    'fields' => array(
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
	
    ),
));

CSF::createSection($prefix_special,array(
	'title'=>'专题设置',
	'fields'=>array(
		array(
            'id'    => 'seo_custom_title',
            'type'  => 'text',
            'title' => 'SEO自定义标题',
            'after'   => '<div class="cs-text-muted">留空则调用默认全局SEO设置</div>'
        ),
        array(
            'id'    => 'seo_custom_keywords',
            'type'  => 'text',
            'title' => 'SEO自定义关键词',
            'after'   => '<div class="cs-text-muted">留空则调用默认全局SEO设置</div>'
        ),
        array(
            'id'    => 'seo_custom_desc',
            'type'  => 'textarea',
            'title' => 'SEO自定义描述',
			'after'   => '<div class="cs-text-muted">留空则调用默认全局SEO设置</div>'
        ),
		array(
			'id'=>'cover',
			'type'=>'media',
			'url'=>false,
			'title'=>'专题封面图',
			'desc'=>'图片大小345×120，内容居中显示'
		),
		// array(
			// 'id'=>'banner',
			// 'type'=>'media',
			// 'url'=>false,
			// 'title'=>'主题Banner图'
		// ),
		// array(
			// 'id'=>'category',
			// 'title'=>'选取关联分类',
			// 'type'=>'checkbox',
			// 'options'=>'categories',
			// 'query_args'=>['hide_empty'=>false],
			// 'desc'=>'注意关联分类在主题设置【常规设置】里面开启，开启后专题将按照选取的分类展示，文章编辑页专题选取将消失'
		// )
	)
));