<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

	CSF::createWidget( 'image_ad', array(
    'title'       => '一张大图及链接',
    'description' => '一个大图一篇文章或链接',
	'fields'=>[
		array(
		'id'=>'ad_image',
		'type'=>'media',
		'title'=>'上传图片',
		'desc'=>'上传图片',
		'url'=>false
		),
		array(
		 'id'=>'ad_content',
		 'type'=>'text',
		 'title'=>'文档ID或页面链接'
		),
		array(
			'id'=>'ad_title',
			'type'=>'text',
			'title'=>'文档标题'
		),
		array(
			'id'=>'ad_nofollow',
			'type'=>'switcher',
			'title'=>'开启nofollow',
			'default'=>false
		)
	]));
	 function image_ad( $args, $instance ) {
		 $url=is_numeric($instance['ad_content'])?get_the_permalink($instance['ad_content']):$instance['ad_content'];
		 $img_src=$instance['ad_image']['url'];
		 $title=$instance['ad_title'];
		 $nofollow=$instance['ad_nofollow']?"rel='nofollow' target='_blank'":"";
		 echo "<div class='w-sidebar'>";
		 echo "<a class='onebigimg' href='{$url}' {$nofollow} title='{$title}'><img src='{$img_src}' title='{$title}'/>";
		 if(!empty($title)){
			echo "<h3>{$title}</h3>";
		 }
   		 echo "</a>";
		 echo "</div>";
	 }

	CSF::createWidget('swiper_image',array(
		'title'=>'多张图幻灯片',
		'description'=>'显示多张幻灯片',
		'fields'=>[
			array(
				'id'=>'swiper_image',
				'type'=>'group',
				'button_title'    => '添加幻灯片',
				'accordion_title' => '添加幻灯片',
				'accordion_title_auto'=>false,
				'accordion_title_prefix'=>'幻灯片',
				"accordion_title_number"=>true,
				'fields'=>[
					array(
						'id'=>'ad_image',
						'type'=>'media',
						'title'=>'上传图片',
						'url'=>false
					),
					array(
					 'id'=>'ad_content',
					 'type'=>'text',
					 'title'=>'文档ID或页面链接'
					),
					array(
						'id'=>'ad_title',
						'type'=>'text',
						'title'=>'文档标题'
					),
					array(
						'id'=>'ad_nofollow',
						'type'=>'switcher',
						'title'=>'开启nofollow',
						'default'=>false
					)
				]
			)
		]
	));

	function swiper_image($args,$instance){
		add_js("swiper.min");
		//add_js("side-swiper");
		add_css('swiper');
		$swiper=$instance['swiper_image'];
		echo '<div class="w-sidebar">	
				<div class="swiper-container w-swiper w-radius swiper-morimg">
					<div class="swiper-wrapper">';
					foreach($swiper as $k=>$v){
						$title=$v['ad_title'];
						$img_src=$v['ad_image']['url'];
						$url=is_numeric($v['ad_content'])?get_the_permalink($v['ad_content']):$v['ad_content'];
						$nofollow=$v['ad_nofollow']?"rel='nofollow' target='_blank'":"";
						echo "<div class='swiper-slide'><a href='{$url}' {$nofollow}><img src='{$img_src}' title='{$title}'/><p>{$title}</p></a></div>";
					}
		echo ' </div>
				<div class="swiper-pagination w-spag"></div>
				<div class="swiper-button-next w-sparrow"></div>
				<div class="swiper-button-prev w-sparrow"></div>
			  </div>
			</div>';
		
	}

	CSF::createWidget('tag_list',array(
			'title'=>'标签云列表',
			'description'=>'显示标签列表',
			'fields'=>[
				array(
					'id'=>'title',
					'title'=>'标题',
					'type'=>'text'
				),
				array(
					'id'=>'tag',
					'title'=>'选择标签',
					'type'=>'checkbox',
					'options'=>'tags',
					'query_args'=>'hide_empty=0&orderby=count&order=desc',
					'desc'=>'如不选择按照文档多少选取'
				),
				array(
					'id'=>'page',
					'title'=>'选择标签云页面',
					'type'=>'radio',
					'options'=>'page'
				)
			]
	));
	
	function tag_list($args,$instance){
		$title=$instance['title'];
        if(in_array('tag', $instance)) {
            $tag=$instance['tag'];
        } else {
            $tag = null;
        }
        if(in_array('page', $instance)) {
            $page=$instance['page'];
        } else {
            $page = null;
        }
		if(!empty($page)){
			$tagurl=get_permalink($page);
		}else{
			$tagurl=home_url('tagslist');
		}
		echo "<div class='w-sidebar'>
				<div class='w-sidr-header'>
					<h3>{$title}</h3>
						<a href='{$tagurl}' title='标签云'>更多></a></div>
						<div class='w-sidr-body w-sidr-litag'><ul>";
			if($tag){
				foreach($tag as $v){
					$tag=get_tag($v);
					$url=get_tag_link($v);
					echo "<li><a href='{$url}' title='{$tag->name}'>{$tag->name}</a></li>";
				}
			}else{
				$tags_list = get_tags('orderby=count&order=DESC&number=32');
				foreach($tags_list as $v){
					$url=get_category_link($v->term_id);
					echo "<li><a href='{$url}' title='{$v->name}'>{$v->name}</a></li>";
				}
			}
		echo "</ul></div></div>";
	}
	
	CSF::createWidget('w_post_list',array(
		'title'	=>'文章列表',
		'description'=>'显示一系列文章',
		'fields'=>[
			array(
				'id'=>'title',
				'title'=>'标题',
				'type'=>'text',
			),
			array(
				'id'=>'order',
				'title'=>'获取类型',
				'type'=>'radio',
				'options'=>[
					'1'=>'最新文章',
					'2'=>'最热文章',
				],
				'default'=>'1'
			),
			array(
				'id'=>'view',
				'title'=>'显示样式',
				'type'=>'radio',
				'options'=>[
					'1'=>'文字列表',
					'2'=>'图文列表',
					'3'=>'双列图片'
				],
				'default'=>'1',
				'desc'=>'根据前端显示调整对应样式',
			),
			array(
				'id'=>'num',
				'title'=>'文章数量',
				'type'=>'text',
				'default'=>6
			),
			array(
				'id'=>'category',
				'title'=>'选择显示的分类',
				'type'=>'radio',
				'options'=>'categories',
				'query_args'=>['exclude'=>1],
				'desc'=>'如果不选择则按当前页面的类型显示'
			)
		]
	));
	function w_post_list($args,$instance){
		global $cat;
        global $tag;
		$title=$instance['title'];
		$order=$instance['order'];
		$view=$instance['view'];
		$num=$instance['num'];
		if(!is_numeric($num)||$num==0){
			$num=6;
		}
		$category=empty($instance['category'])?null:$instance['category'];
		if($order==1){//最新文章
			$args=['posts_per_page'=>$num,'order'=>'desc','orderby'=>'date'];
		}else{
			$args=['posts_per_page'=>$num,'order'=>'desc','orderby'=>'meta_value_num','meta_key'=>'views']; 
		}
		if(!empty($category)){
				$args['cat']=$category;
			}else{
				if(is_category()){
					$args['cat']=$cat;
				}
				if(is_tag()){
					$args['tag']=$tag;
				}
				if(is_single()){
					$category=get_the_category();
					$args['cat']=$category[0]->term_id;
				}
			}
			if(empty($args['cat'])){
				$a='';
			}else{
				$a=get_category_link($args['cat']);
				$a="<a href='{$a}'>更多></a>";
			}
			if(!empty($args['tag'])){
				$a=get_category_link($args['tag']);
				$a="<a href='{$a}'>更多></a>";
			}
			$the_query=get_cache_query($args);
			if($view==1){	//样式一
				echo "<div class='w-sidebar'>
						<div class='w-sidr-header'>
						<h3>{$title}</h3>
							{$a}
						</div>				
						<div class='w-sidr-body w-sidr-li'>
						<ul>";
						if($the_query->have_posts()){
							while($the_query->have_posts()){
								$the_query->the_post();
								$url=get_permalink();
								$title=get_the_title();
								echo "<li><a href='{$url}' title='{$title}'>{$title}</a></li>";
							}
							wp_reset_postdata();
						}	
				echo "</ul></div></div>";	
			}elseif($view==2){//样式二
				echo "<div class='w-sidebar'>
						<div class='w-sidr-header'>
						<h3>{$title}</h3>
						{$a}
						</div>				
						<div class='w-sidr-body w-sidr-imgli'>";
						if($the_query->have_posts()){
							while($the_query->have_posts()){
								$the_query->the_post();
								$pid=get_the_ID();
								$url=get_permalink();
								$title=get_the_title();
								$img_src=get_post_img();
								$time=timeago(get_the_time("Y-m-d H:i:s"));
								$view=get_post_meta($pid,'views',true);
								$view=empty($view)?0:shortnum($view);
								echo "<div class='w-sdimg'>
										<a href='{$url}' title='{$title}'>
										<div class='w-sdimg-img'>
										<img src='{$img_src}' title='{$title}'/>
										</div>
										<div class='w-sdimg-wp'>
											<h4>{$title}</h4>
											<div class='w-lswp-bottom'>
												<span><i class='iconfont icon-shijian'></i>{$time}</span>
												<span><i class='iconfont icon-yanjing'></i>{$view}</span>
											</div>
										</div>
										</a>
										</div>";
							}
							wp_reset_postdata(); 
						}
				echo "</div></div>";
			}elseif($view==3){
				echo "<div class='w-sidebar'>
						  <div class='w-sidr-header'>
							<h3>{$title}</h3>
								{$a}</div>
						  <div class='w-sidr-body w-sidr-imgli  w-sidr-imgli2'>";
						if($the_query->have_posts()){
							while($the_query->have_posts()){
								$the_query->the_post();
								$link=get_permalink();
								$title=get_the_title();
								$img_src=get_post_img(138,90);
								echo "<div class='w-sdimg2'>
										  <a href='{$link}' title='{$title}'>
											<div class='w-sdimg-img2'>
											  <img src='{$img_src}' title='{$title}'></div>
											  <h4>{$title}</h4>		
										  </a>
										</div>";
							}
							wp_reset_postdata();
						}
						echo "</div></div>";
			}
	}


	
	
	
	
	
	register_sidebar(array(
	  'name' => __( '首页侧边栏' ),
	  'id' => 'home_side',
	  'description' => __( '在首页的侧边栏' ),
	  'class'=>'',
	  'before_title' => '<h2>',
	  'after_title' => '</h2>',
	  'before_widget'=>'<div>',
	  'after_widget'=>'</div>'
	  
	));
	
	register_sidebar(array(
	  'name' => __( '分类页侧边栏' ),
	  'id' => 'category_side',
	  'description' => __( '在分类页显示的侧边栏' ),
	  'class'=>'',
	  'before_title' => '<h2>',
	  'after_title' => '</h2>',
	  'before_widget'=>'<div>',
	  'after_widget'=>'</div>'
	  
	));
	
	register_sidebar(array(
	  'name' => __('内容页侧边栏'),
	  'id' => 'content_side',
	  'description' => __('在内容页显示的侧边栏'),
	  'class'=>'',
	  'before_title' => '<h2>',
	  'after_title' => '</h2>',
	  'before_widget'=>'<div>',
	  'after_widget'=>'</div>'  
	));
	
	
	
	
	
	//dynamic_sidebar('home_side');调用
	add_action( 'widgets_init', 'my_unregister_widgets' );   
	function my_unregister_widgets() {   
		unregister_widget( 'WP_Widget_Archives' );   
		unregister_widget( 'WP_Widget_Calendar' );   
		unregister_widget( 'WP_Widget_Categories' );   
		unregister_widget( 'WP_Widget_Links' );   
		unregister_widget( 'WP_Widget_Meta' );   
		unregister_widget( 'WP_Widget_Pages' );   
		unregister_widget( 'WP_Widget_Recent_Comments' );   
		unregister_widget( 'WP_Widget_Recent_Posts' );   
		unregister_widget( 'WP_Widget_RSS' );   
		unregister_widget( 'WP_Widget_Search' );   
		unregister_widget( 'WP_Widget_Tag_Cloud' );  
		unregister_widget('WP_Widget_Media_Image');
		unregister_widget('WP_Widget_Media_Gallery');
		unregister_widget('WP_Widget_Media_Audio');
		unregister_widget('WP_Widget_Media_Video');
		unregister_widget( 'WP_Widget_Text' );   
		unregister_widget( 'WP_Nav_Menu_Widget' );   
	}  
	
	