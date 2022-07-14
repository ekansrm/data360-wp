<?php if (!defined('ABSPATH')) {die;} 



$prefix = CS_OPTION;


CSF::createOptions($prefix, array(
    'menu_title' => '主题设置',
    'menu_slug'  => 'csf-wpzt',
	'menu_type'       => 'menu', // menu, submenu, options, theme, etc.
	'ajax_save'       => true,
));

CSF::createSection($prefix,array(
	'title'=>'常规设置',
	'name'=>'img_set',
	'fields'=>[
		array(
		'id'        => 'logo',
		'type'      => 'media',
		'url'		=>false,
		'title'     => '网站 LOGO',
		'desc'      => '上传网站 LOGO',
		'add_title' => '上传 LOGO',
	),
	
	array(
		'id'        => 'favicon',
		'type'      => 'media',
		'url'		=>false,
		'title'     => '网站 Favicon图标',
		'desc'      => '上传网站 Favicon图标',
		'add_title' => '上传 Favicon',
	),
	array(
		'id'=>'main-color',
		'type'=>'color',
		'title'=>'主色调',
		'default'=>'#e93274'
	),
	array(
		'id'=>'open-fillet',
		'type'=>'switcher',
		'title'=>'开启圆角',
		'default'=>true
	),
    array(
		'id'          => 'timthumb',
		'type'        => 'gallery',
		'title'       => '默认缩略图',
		'after'    	  => '<p class="cs-text-muted">（请在下面的选项中填写你上传了几张默认缩略图）如果有设置特色图像将优先显示特色图像，没有特色图像则显示文章内第一张图片，文章内没有图片才显示此处设置的图像</p>',
		'add_title'   => '添加默认缩略图',
		'edit_title'  => '编辑默认缩略图',
		'clear_title' => '全部删除',
    ),	
    array(
		'id'      => 'timthumb_num',
		'type'    => 'number',
		'title'   => '你上传了几张默认缩略图？',
		'after'   => ' <i class="cs-text-muted">张图像随机显示</i>',
		'default' => '1',
    ),
	]
));
CSF::createSection($prefix,array(
	'name'=>'index-set',
	'title'=>'首页内容',
	'fields'=>[
		
		array(
          'id'              => 'home_mod',
          'type'            => 'group',
          'title'           => '添加首页模块',
          'button_title'    => '添加首页模块',
          'accordion_title' => '添加首页模块',
		   'accordion_title_auto'=>true,
		  'accordion_title_prefix'=>'模块',
		  "accordion_title_number"=>true,
          'fields'          => [
				array(
					'id'=>'index-mod',
					'type'=>'image_select',
					'title'=>'选择模块类型',
					'options'=>[
						'1'=>WPZT_IMG.'/mod-1.png',
						'2'=>WPZT_IMG.'/mod-2.png',
						'3'=>WPZT_IMG.'/mod-3.png',
						'4'=>WPZT_IMG.'/mod-4.png',
						'5'=>WPZT_IMG.'/mod-5.png',
						'6'=>WPZT_IMG.'/mod-ad.png'
					]
				),
				array(
					'id'=>'banner',
					'type'=>'group',
					'title'=>'轮播图',
					"accordion_title_number"=>true,
					'accordion_title_auto'=>true,
					'fields'=>[
						array(
							'id'=>'title',
							'type'=>'text',
							'title'=>'标题'
						),
						array(
							'id'=>'img',
							'type'=>'media',
							'title'=>'图片',
							'desc'=>'710px×350px',
							'url'=>false
						),
						array(
							'id'=>'link',
							'type'=>'text',
							'title'=>'链接'
						)
					],
					'dependency'=>array('index-mod','==','1')
				),
				array(
					'id'=>'title',
					'type'=>'text',
					'title'=>'模块标题',
					'dependency'=>array('index-mod','any','2,3,4,5')
				),
				array(
					'id'=>'index-mod-2-cat1',
					'title'=>'第一列分类',
					'type'=>'radio',
					'options'=>'categories',
					'query_args'=>['hide_empty'=>false],
					'dependency'=>array('index-mod','==','2')
				),
				array(
					'id'=>'index-mod-2-cat2',
					'title'=>'第二列分类',
					'type'=>'radio',
					'options'=>'categories',
					'query_args'=>['hide_empty'=>false],
					'dependency'=>array('index-mod','==','2')
				),
				array(
					'id'=>'index-mod-2-cat3',
					'title'=>'第三列分类',
					'type'=>'radio',
					'options'=>'categories',
					'query_args'=>['hide_empty'=>false],
					'dependency'=>array('index-mod','==','2')
				),
				array(
					'id'=>'index-mod-one-cat',
					'title'=>'分类',
					'type'=>'radio',
					'options'=>'categories',
					'query_args'=>['hide_empty'=>false],
					'dependency'=>array('index-mod','any','3,4,5')
				),
				array(
					'id'=>'ad',
					'type'=>'textarea',
					'title'=>'PC端广告位',
					'dependency'=>array('index-mod','==','6'),
					'desc'=>'<xmp>宽度：710px，直接填写广告HTML代码，图片链接广告代码参考：<a href="广告链接" target="_blank"><img src="图片链接"></a></xmp>'
				),
				array(
					'id'=>'m-ad',
					'type'=>'textarea',
					'title'=>'移动端广告位',
					'dependency'=>array('index-mod','==','6'),
					'desc'=>'<xmp>宽度：450px，直接填写广告HTML代码，图片链接广告代码参考：<a href="广告链接" target="_blank"><img src="图片链接"></a></xmp>'
				)
				
				
		  ]),
		
	]
));



CSF::createSection($prefix,array(
	'name'=>'login-set',
	'title'=>'用户设置',
	'fields'=>[
		array(
			'type'=>'notice',
			'title'=>'社交账号登录',
			'content'=>'社交登录的回调地址是:'.home_url('logincallback')
		),
		array(
			'id'=>'open_qq_login',
			'type'=>'switcher',
			'title'=>'开启QQ登录',
			'default'=>false
		),
		array(
			'id'=>'qq_login_id',
			'type'=>'text',
			'title'=>'QQ登录APP ID',
			'dependency'=>array('open_qq_login','==',true)
		),
		array(
			'id'=>'qq_login_key',
			'type'=>'text',
			'title'=>'QQ登录APP Key',
			'dependency'=>array('open_qq_login','==',true)
		),
		array(
			'id'=>'open_wechat_login',
			'type'=>'switcher',
			'title'=>'开启微信登录',
			'default'=>false
		),
		array(
			'id'=>'wechat_login_id',
			'type'=>'text',
			'title'=>'微信登录APP ID',
			'dependency'=>array('open_wechat_login','==',true)
		),
		array(
			'id'=>'wechat_login_key',
			'type'=>'text',
			'title'=>'微信登录APP Key',
			'dependency'=>array('open_wechat_login','==',true)
		),
		array(
			'id'=>'open_weibo_login',
			'type'=>'switcher',
			'title'=>'开启微博登录',
			'default'=>false
		),
		array(
			'id'=>'weibo_login_id',
			'type'=>'text',
			'title'=>'微博登录APP ID',
			'dependency'=>array('open_weibo_login','==',true)
		),
		array(
			'id'=>'weibo_login_key',
			'type'=>'text',
			'title'=>'微博登录APP KEY',
			'dependency'=>array('open_weibo_login','==',true)
		),
		array(
			'type'=>'notice',
			'title'=>'找回密码的邮箱设置(注意SMTP用SSL加密)'
		),
		array(
			'id'=>'email_fromname',
			'type'=>'text',
			'title'=>'发件人昵称',
			'after'=>'例如：主题盒子'
		),
		array(
			'id'=>'email_host',
			'type'=>'text',
			'title'=>'邮箱SMTP服务器',
			'after'=>'例如：smtp.qq.com'
		),
		array(
			'id'=>'email_username',
			'type'=>'text',
			'title'=>'邮箱用户名',
			'after'=>'例如：zzuli@qq.com'
		),
		array(
			'id'=>'email_password',
			'type'=>'text',
			'title'=>'SMTP授权码',
			'after'=>'授权码非邮箱密码，例如QQ邮箱：设置->账户->SMTP服务(开启)->生成授权码'
		),
		//投稿设置
		array(
			'type'=>'notice',
			'title'=>'投稿设置'
		),
		array(
			'id'=>'user_post_cat',
			'title'=>'允许用户投稿分类',
			'type'=>'checkbox',
			'inline'=>true,
			'options'=>'categories',
			'query_args'=>['hide_empty'=>false],
		),
		
		array(
			'id'=>'user_post_status',
			'title'=>'用户投稿是否直接发布',
			'type'=>'switcher',
			'default'=>false
		)
	]
));

 

/*广告位暂时停用*/
CSF::createSection($prefix,array(
	'name'=>'ad-set',
	'title'=>'广告位设置',
	'fields'=>[
		array(
			'id'=>'category-ad',
			'title'=>'列表页广告位',
			'type'=>'textarea',
			'default'=>'',
			'desc'=>'<xmp>宽度：710px，直接填写广告HTML代码，图片链接广告代码参考：<a href="广告链接" target="_blank"><img src="图片链接"></a></xmp>'
		),
		array(
			'id'=>'m-category-ad',
			'title'=>'手机端列表页广告位',
			'type'=>'textarea',
			'default'=>'',
			'desc'=>'<xmp>宽度：450px，直接填写广告HTML代码，图片链接广告代码参考：<a href="广告链接" target="_blank"><img src="图片链接"></a></xmp>'
		),
		
		array(
			'id'=>'content-ad',
			'title'=>'内页上部广告位',
			'type'=>'textarea',
			'default'=>'',
			'desc'=>'<xmp>宽度：850px，直接填写广告HTML代码，图片链接广告代码参考：<a href="广告链接" target="_blank"><img src="图片链接"></a></xmp>'
		),
		array(
			'id'=>'m-content-ad',
			'title'=>'手机端内页上部广告位',
			'type'=>'textarea',
			'default'=>'',
			'desc'=>'<xmp>宽度：450px，直接填写广告HTML代码，图片链接广告代码参考：<a href="广告链接" target="_blank"><img src="图片链接"></a></xmp>'
		),
		array(
			'id'=>'content-ad-b',
			'title'=>'内页下部广告位',
			'type'=>'textarea',
			'default'=>'',
			'desc'=>'<xmp>宽度：850px，直接填写广告HTML代码，图片链接广告代码参考：<a href="广告链接" target="_blank"><img src="图片链接"></a></xmp>'
		),
		array(
			'id'=>'m-content-ad-b',
			'title'=>'手机端内页下部广告位',
			'type'=>'textarea',
			'default'=>'',
			'desc'=>'<xmp>宽度：450px，直接填写广告HTML代码，图片链接广告代码参考：<a href="广告链接" target="_blank"><img src="图片链接"></a></xmp>'
		),
	]
));


CSF::createSection($prefix,array(
	'name'=>'footer-set',
	'title'=>'页脚设置',
	'fields'=>[
		array(
			'id'	=>'footer_copyright',
			'type'	=>'textarea',
			'title'=>'版权信息',
			'default'=>'Copyright 2015-2020 www.wpzt.net ©All Rights Reserved.版权所有，未经授权禁止复制或建立镜像，违者必究！'
		),
		array(
			'id'		=> 'footer_icp',
			'type'		=> 'text',
			'title'		=> 'ICP备案号',
		),
		array(
			'id'		=>'footer_gaba',
			'type'	=>'text',
			'title'	=>'公安备案'
		),
		array(
			'id'		=>'footer_gaba_link',
			'type'		=>'text',
			'title'		=>'公安备案地址'
		),
		array(
			'id'		=> 'foot_link',
			'type'		=> 'switcher',
			'title'		=> '友情链接',
			'desc'		=> '选择是否显示首页底部 友情链接',
			'default'	=> true
		),
		array(
			'id'		=> 'foot_link_cat',
			'type'		=> 'radio',
			'title'		=> '选择链接分类',
			'class'		=> 'horizontal',
			'options'	=> 'tags',
			'query_args'=>['taxonomies'=>'link_category','hide_empty'=>false],
			'after'		=> '<p class="cs-text-muted">如果此处没有显示您创建的链接分类，是因为您的链接分类中没有添加链接。</p>',
			'dependency'=> array( 'foot_link', '==', true )
		),
		
	]
));

CSF::createSection($prefix,array(
	'name'=>'sys-set',
	'title'=>'系统优化',
	'fields'=>[
		array(
			'id'		=> 'thumbnail',
			'type'		=> 'radio',
			'title'		=> '选择缩略图剪裁模式',
		    'options'	=> array(
				'timthumb'	=> '使用timthumb.php剪裁缩略图',
				'default'	=> '输出原图（如果需要使用七牛或是阿里云OSS加速，请在【扩展功能 - CDN加速】中进行配置）',
		    ),
		    'default'	=> 'timthumb',
			'desc'		=> '如果开启了【扩展功能 - CDN加速】此处设置将不生效'
    	),
		
		array(
			'id'      => 'wpzt_option_thumbnail',
			'type'    => 'switcher',
			'title'   => '禁止WP默认缩略图',
			'desc'    => '彻底禁止WordPress自动生成各类尺寸的缩略图。',
			'default' => true
		),
		array(
			'id'      => 'wpzt_no_gutenberg',
			'type'    => 'switcher',
			'title'   => '彻底禁用古腾堡编辑器',
			'default' => true
		),
		// array(
			// 'id'      => 'wpzt_article',
			// 'type'    => 'switcher',
			// 'title'   => '登陆后台跳转到文章列表',
			// 'desc'    => 'WordPress登陆后一般是显示仪表盘页面，开启这个功能后登陆后台默认显示文章列表（默认开启）。',
			// 'default' => true
		// ),
		array(
			'id'      => 'wpzt_wp_head',
			'type'    => 'switcher',
			'title'   => '移除顶部多余信息',
			'desc'    => '移除WordPress Head 中的多余信息，能够有效的提高网站自身安全（默认开启）。',
			'default' => true
		),
		array(
			'id'      => 'wpzt_api',
			'type'    => 'switcher',
			'title'   => '禁用REST API',
			'desc'    => '禁用REST API、移除wp-json链接（默认关闭，如果你的网站没有做小程序或是APP，建议开启这个功能，禁用REST API）。',
			'default' => false
		),
		array(
			'id'      => 'wpzt_pingback',
			'type'    => 'switcher',
			'title'   => '禁用XML-RPC',
			'desc'    => '此功能会关闭 XML-RPC 的 pingback 端口（默认开启，提高网站安全性）。',
			'default' => false
		),
		array(
			'id'      => 'wpzt_feed',
			'type'    => 'switcher',
			'title'   => '禁用Feed',
			'desc'    => 'Feed易被利用采集，造成不必要的资源消耗（默认开启）。',
			'default' => true
		),
		array(
			'id'      => 'wpzt_category',
			'type'    => 'switcher',
			'title'   => '去除分类标志',
			'desc'    => '去除链接中的分类category标志，有利于SEO优化，每次开启或关闭此功能，都需要重新保存一下固定链接！（默认关闭）。',
			'default' => true
		),
		array(
			'id'      => 'wpzt_privacy',
			'type'    => 'switcher',
			'title'   => '移除后台隐私',
			'desc'    => '彻底删除WordPress后台隐私相关设置。',
			'default' => true
		),
		array(
			'id'      => 'wpzt_revision',
			'type'    => 'switcher',
			'title'   => '禁用日志修订功能',
			'desc'    => '文章修订会在 Posts 表中插入多条历史数据，造成 Posts 表冗余，建议屏蔽文章修订功能，提高数据库效率。',
			'default' => true
		),
		array(
			'id'      => 'wpzt_delete_post_attachments',
			'type'    => 'switcher',
			'title'   => '删除文章时删除图片附件',
			'default' => true
		),
		array(
			'id'      => 'wpzt_v2ex',
			'type'    => 'switcher',
			'title'   => 'Gravatar镜像服务',
			'desc'    => '使用国内的Gravatar镜像服务，提高网站加载速度，https://cdn.v2ex.com/gravatar',
			'default' => true
		),
		array(
			'id'      => 'wpzt_upload_img_rename',
			'type'    => 'switcher',
			'title'   => '上传图片重命名',
			'desc'    => '上传的图片使用日期格式重命名',
			'default' => true
		),
		array(
			'id'      => 'wpzt_remove_script_version',
			'type'    => 'switcher',
			'title'   => '去除前端版本号',
			'desc'    => '去除前端加载的css和js后面的版本号',
			'default' => true
		),
		array(
			'id'      => 'wpzt_all_settings',
			'type'    => 'switcher',
			'title'   => '高级设置',
			'desc'    => '在设置菜单下面显示高级设置选项（不懂的不要乱设置）',
			'default' => false
		),
		array(
			'id'      => 'wpzt_language',
			'type'    => 'switcher',
			'title'   => '禁止前台加载语言包',
			'desc'    => '禁止前台加载语言包可提升 0.1-0.5 秒不等的加载时间',
			'default' => true
		),
		array(
			'id'=>'wpzt_wp_update',
			'type'=>'switcher',
			'title'=>'不检测更新',
			'desc'=>'由于wordpress官网在国内访问缓慢或经常无法访问,可以暂时关闭Wordpress更新检测',
			'default'=>true
		)
	]
));

CSF::createSection($prefix,array(
	'name'=>'other-opt',
	'title'=>'扩展',
	'fields'=>[
		array(
			'id'      => 'wpzt_sitemap',
			'type'    => 'switcher',
			'title'   => '站点地图（Sitemap）',
			'desc'    => '开启后刷新一下页面，在外观 - 站点地图 中进行设置<br/>
			自动生成xml文件，遵循Sitemap协议，用于指引搜索引擎快速、全面的抓取或更新网站上内容。兼容百度、google、360等主流搜索引擎。',
			'default' => false
		),
		
        array(
            'type'			=> 'notice',
            'class'			=> 'info',
            'content'		=> 'CDN加速，支持七牛云储存和阿里云OSS',
			'dependency'	=> array( 'cdn_switch', '==', true )
        ),
		
		array(
			'id'      => 'cdn_switch',
			'type'    => 'switcher',
			'title'   => 'CDN加速，支持七牛云储存和阿里云OSS以及腾讯云COS',
			'default' => false
		),
        array(
			'id'      => 'cdn_type',
			'type'    => 'select',
			'title'   => '选择云储存',
			'options' => array(
				'qiniu'		=> '七牛云储存',
				'alioss'	=> '阿里云OSS',
				'txcos'		=> '腾讯云COS',
			),
			'dependency'	=> array( 'cdn_switch', '==', true )
        ),
		array(
			'id'   =>'open_cdn',
			'type'=>'switcher',
			'title'=>'开启CDN',
			'default'=>false,
			'dependency'	=> array( 'cdn_switch', '==', true )
		),
        array(
            'id'		   => 'cdn_url',
            'type'		   => 'text',
            'title'		   => '加速域名',
			'desc'   	   => '你的加速域名',
			'after'		   => '<p class="cs-text-muted">不要忘记了“http(s)://”</p>',
			'attributes'   => array('style'=> 'width: 50%;'),
			'dependency'	=> array( 'cdn_switch', '==', true )
        ),
        array(
            'id'		   => 'cdn_file_format',
            'type'		   => 'text',
            'title'		   => '镜像文件格式',
			'default'	   => 'js|css|png|jpg|jpeg|gif|ico|7z|zip|rar|pdf|ppt|wmv|mp4|avi|mp3|txt',
			'desc'   	   => '在输入框内添加准备镜像的文件格式，比如png|jpg|jpeg|gif|ico|html|7z|zip|rar|pdf|ppt|wmv|mp4|avi|mp3|txt（使用|分隔）',
			'attributes'   => array('style'=> 'width: 50%;'),
			'dependency'	=> array( 'cdn_switch', '==', true )
        ),
        array(
            'id'		   => 'cdn_mirror_folder',
            'type'		   => 'text',
            'title'		   => '镜像文件夹',
			'default'	   => 'wp-content|wp-includes',
			'desc'		   => '在输入框内添加准备镜像的文件夹，比如wp-content|wp-includes（使用|分隔）',
			'attributes'   => array('style'=> 'width: 50%;'),
			'dependency'	=> array( 'cdn_switch', '==', true )
        ),
		array(
			'id'			=> 'admin_cdn',
			'type'			=> 'switcher',
			'title'			=> '后台媒体库同时使用CDN加速',
			'dependency'	=> array( 'cdn_switch', '==', true ),
			'default'		=> false
		),  
		// array(
			// 'type'=>'backup',
			// 'title'=>'备份数据'
		// )
	]
));

CSF::createSection($prefix,array(
	'name'=>'add-code',
	'title'=>'添加代码',
	'fields'=>[
			array(
				  'id'      => 'head_code',
				  'type'    => 'code_editor',
				  'title'   => '添加代码到头部',
				  'after'    => '<p class="cs-text-muted">出现在网站 head 中，主要用于验证网站...</p>',
				  'settings'=>[
						'theme'=>'mbo'
				  ]
				),
				array(
				  'id'      => 'foot_code',
				  'type'    => 'code_editor',
				  'title'   => '添加代码到页脚',
				  'after'    => '<p class="cs-text-muted">出现在网站底部 body 前，主要用于站长统计代码...</p>',
				  'settings'=>[
						'theme'=>'mbo'
				  ]
				)
	]
));



CSF::createSection($prefix, array(
   'name'        => 'index-seo',
  'title'       => 'SEO设置',
  'icon'        => 'iconfont icon-wz-seo',
  'fields'      => array(

       array(
            'type' => 'notice',
            'class' => 'info',
            'content' => '全局SEO功能设定',
        ),
		array(
			'id'=>'wpzt_add_alttitle',
			'type'=>'switcher',
			'title'=>'文章图片自动添加alt及title',
			'default'=>true
		),
		array(
			'id'=>'wpzt_del_link',
			'type'=>'switcher',
			'title'=>'自动清除文章内外链',
			'default'=>true,
			'desc'=>'开启后文章内的外链会全部自动清除'
		),
        array(
            'id' => 'seo_auto_des',
            'type' => 'switcher',
            'title' => '文章页描述',
            'desc' => '开启后将自动截取文章内容作为文章description标签',
            'default' => true
        ),

        array(
            'id' => 'seo_auto_des_num',
            'type' => 'text',
            'title' => '自动截取字节数',
            'default' => '120',
            'dependency' => array('seo_auto_des', '==', true),
        ),

        array(
            'id' => 'seo_sep',
            'type' => 'text',
            'title' => 'Title后缀分隔符',
            'default' => ' - ',
        ),

        array(
            'type' => 'notice',
            'class' => 'info',
            'content' => '首页SEO设置',
        ),
        array(
            'id' => 'seo_home_title',
            'type' => 'text',
            'title' => '首页标题',
            'help' => '关键词使用英文逗号隔开',
        ),

        array(
            'id' => 'seo_home_keywords',
            'type' => 'text',
            'title' => '首页关键词',
        ),

        array(
            'id' => 'seo_home_desc',
            'type' => 'textarea',
            'title' => '首页描述',
        ),
		array(
			'type'=>'notice',
			'class'=>'info',
			'content'=>'百度资源提交设置'
		),
		array(
			'id'=>'bdzy_url',
			'type'=>'text',
			'title'=>'百度资源提交接口调用地址',
			'desc'=>'添加接口调用地址如：http://data.zz.baidu.com/urls?site=xxxxx&token=xxxxxxxx'
		),

  )
));

CSF::createSection($prefix,array(
	'name'=>'cache-set',
	'title'=>'缓存设置',
	'fields'=>[
		array(
		'id'=>'cache-type',
		'title'=>'缓存类型',
		'type'=>'radio',
		'options'=>array(
			'1'=>'文件缓存',
			'2'=>'redis',
			'3'=>'memcached'
		),
		'default'=>'1',
		'desc'=>'使用redis请确认redis正确安装，及其PHP扩展正确安装,<br/>
		使用memcached请确认memchached正确安装，及其PHP扩展正确安装<br/>
		redis和memcached谨慎选用,配置错误可能造成网站前端无法访问，并确认服务器有足够内存'
		),
		array(
			'id'=>'cache-server',
			'title'=>'缓存服务器',
			'type'=>'text',
			'default'=>'127.0.0.1',
			'dependency' => array('cache-type', '>', 1),
			
		),
		array(
			'id'=>'cache-port',
			'title'=>'缓存服务器端口',
			'type'=>'text',
			'default'=>'6379',
			'desc'=>'memcached默认为11211，redius默认为6379',
			'dependency'=>array('cache-type','>',1)
		),
		array(
			'id'=>'cache-user',
			'title'=>'用户名',
			'type'=>'text',
			'dependency'=>array('cache-type','==',3),
			'desc'=>'没设置密码不要填写用户名'
		),
		array(
			'id'=>'cache-pwd',
			'title'=>'密码',
			'type'=>'text',
			'dependency'=>array('cache-type','>',1),
			'default'=>'',
			'desc'=>'默认不用填写'
		),
		array(
			'id'=>'cache-save-post',
			'title'=>'发布文章自动清除缓存',
			'type'=>'switcher',
			'desc'=>'发布文章时自动清除缓存，如不选择则手动清除缓存',
			'default'=>false
		)
		
		
	]
));

CSF::createSection($prefix,array(
	'name'=>'site-auth',
	'title'=>'关于网站授权',
	'fields'=>[
	  array(
		'id'=>'auth-key',
		'class'=>'content',
		'type'=>'notice',
		'content'=>'为了以后能正常更新和使用，请去<a href="https://www.wpzt.net" target="_blank">主题盒子</a>获取授权'
		)
	]
));


function clearoptioncache(){
	admin_clear_cache();
	wpzt\Cache::clear(null);
}
add_action('csf_'.$prefix.'_save_after','clearoptioncache',10,0);