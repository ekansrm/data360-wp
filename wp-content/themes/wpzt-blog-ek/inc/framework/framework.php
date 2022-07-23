<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
//document http://codestarframework.com/documentation/#/configurations

defined('CS_OPTION')     or  define( 'CS_OPTION',     '_cs_options' );//option prefix
defined('CS_POST_OPTION') or define('CS_POST_OPTION','extend_info');
defined('CS_TAXONOMY_OPTION')or define('CS_TAXONOMY_OPTION','category_options');
defined('CS_PROFILE_OPTION')or define('CS_PROFILE_OPTION','cs_profile_option');
defined('CS_TAXONOMY_TAG')or define('CS_TAXONOMY_TAG','tag_options');
defined('CS_TAXONOMT_SPECIAL')or define('CS_TAXONOMT_SPECIAL','_custom_special_options');
defined('CS_MENU_OPTION')or define('CS_MENU_OPTION','_menu_options');

if ( ! function_exists( 'wpzt' ) ) {
  function wpzt( $option_name = '', $default = '' ) {
	  if(wpzt\Cache::has('wpzt_option')){
		  $options=wpzt\Cache::get('wpzt_option');
		  if(!empty($options[$option_name])){
			  return $options[$option_name];
		  }else{
			  return ( ! empty( $default ) ) ? $default : null;
		  }
	  }else{//以下基本不会执行
		  $options=get_option( CS_OPTION );
		  wpzt\Cache::set('wpzt_option',$options,0);
		  if( ! empty( $option_name ) && ! empty( $options[$option_name] ) ) {
			   return $options[$option_name];
		  }else{
			  return ( ! empty( $default ) ) ? $default : null;
		  }
	  }
  }
}

if(!function_exists('wpzt_img')){
	function wpzt_img ($option_name,$default=''){
		if(is_array($option_name)){
			return $option_name['url'];
		}
		if(is_numeric($option_name)){	//直接传入图片资源ID
			$id_url=wp_get_attachment_image_src( $option_name, 'full' );
			return $id_url[0];
		}
		$cs_id= wpzt($option_name);
		if (!empty($cs_id )){
			if(is_array($cs_id)){
				return $cs_id['url'];
			}
			$id_url= wp_get_attachment_image_src( $cs_id, 'full' );
			return $id_url[0];
		}
		elseif (empty($cs_id )){
			return $default;
			}
		}
}


if(!function_exists('wpzt_post')){		//文章
	function wpzt_post($post_id,$option=''){
		$meta = get_post_meta( $post_id, CS_POST_OPTION, true );
		if(!empty($option)&&!empty($meta)){
		return $meta[$option];
		}else{
			return $meta;
		}
	}
}

if(!function_exists('wpzt_term')){	//分类
	function wpzt_term($term_id,$option=''){
		$meta = get_term_meta( $term_id,CS_TAXONOMY_OPTION, true );
		if(!empty($option)){
			return $meta[$option];
		}else{
			return $meta;
		}
	}
}
if(!function_exists('wpzt_tag')){	//分类
	function wpzt_tag($term_id,$option=''){
		$meta = get_term_meta( $term_id,CS_TAXONOMY_TAG, true );
		if(!empty($option)){
			return $meta[$option];
		}else{
			return $meta;
		}
	}
}



if(!function_exists('wpzt_profile')){	//个人用户信息
	function wpzt_profile($uid,$option=''){
		$user_meta = get_user_meta( $uid,CS_PROFILE_OPTION, true );
		if(!empty($option)){
			return $user_meta[$option];
		}else{
			return $user_meta;
		}
	}
}

require_once  __DIR__ .'/classes/setup.class.php';
require_once __DIR__ .'/config/widgets.php';

	
	require_once __DIR__ .'/config/options.php';
	require_once __DIR__ .'/config/profile.php';
	require_once __DIR__ .'/config/metabox.php';
	
	require_once __DIR__ .'/config/shortcoder.php';
	require_once __DIR__ .'/config/taxonomy.php';
	require_once __DIR__ .'/config/menu.php';
