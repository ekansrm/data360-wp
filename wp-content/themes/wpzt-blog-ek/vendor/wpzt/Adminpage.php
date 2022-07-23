<?php
namespace wpzt;

class Adminpage{	//后台页面添加类
	
	function __construct(){
			/*************管理后台添加页面************/
		add_action("admin_menu",array($this,'add_all_page'));
		
	}
	
	//function add_managecache_center(){
	//	add_menu_page('更新缓存', '更新缓存', 'administrator',  'wpzt_clearcache', array($this,'soft_admin_cache'), '', 100);
		//页面标题，菜单标题，权限，urlpage即page=wpzt_admin_page，回调方法，菜单图标，菜单位置
		//add_submenu_page('wpzt_clearcache','清除缓存','清除缓存','administrator','clear_cache','clear_cache');
		//父菜单名称，页面标题，菜单标题，权限，urlpage,回调方法
	//	$this->add_page('更新缓存','clearcachepage');
	//}

	// function soft_admin_cache(){
		// include WPZT_DIR.'/admin/clearcachepage.php';
	// }
	
	function add_all_page(){	//所有页面在这里添加
		$this->add_page('更新缓存','clearcachepage');
		// $this->add_page('商城管理','manageshop');
		// $this->add_sub_page('manageshop','未付视频订单','unpayorderlist');
		// $this->add_sub_page('manageshop','已付视频订单','payorderlist');
		// $this->add_sub_page('manageshop','未付VIP订单','unpayviplist');
		// $this->add_sub_page('manageshop','已付VIP订单','payviplist');
		// $this->add_sub_page('manageshop','用户操作','op-user');
		//$this->add_sub_page('manageshop','提现申请','withdraw');
		//$this->add_sub_page('manageshop','资金流水','accountinfo');
	}
	
	function add_page($title,$pageurl){	//添加页面
		add_menu_page($title,$title,'administrator',$pageurl,function()use($pageurl){
			include WPZT_DIR.'/admin/'.$pageurl.'.php';
			},'',100);
	}
	
	function add_sub_page($parenturl,$title,$pageurl){
		add_submenu_page($parenturl,$title,$title,'administrator',$pageurl,function() use($pageurl){
			include WPZT_DIR.'/admin/'.$pageurl.'.php';
		});
	}
	
	
}