<?php if (!defined('ABSPATH')) {die;} 

$prefix_menu_option=CS_MENU_OPTION;

 CSF::createNavMenuOptions( $prefix_menu_option, array(
    'data_type' => 'serialize', 
  ));
  
   CSF::createSection( $prefix_menu_option, array(
    'fields' => [
	array(
		'id'=>'icon',
		'type'=>'media',
		'title'=>'上传图片',
		'url'=>false,
		'desc'=>'侧边栏菜单图标,大小25px×25px'
	)
	]
	));