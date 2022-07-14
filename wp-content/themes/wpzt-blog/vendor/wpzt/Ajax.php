<?php
namespace wpzt;
use wpzt\Cache;
use wpzt\form\Validate as Val;
use wpzt\form\Request as Req;
use wpzt\pay\Common as Com;

class Ajax{		//ajax处理
	function __construct(){
		add_action( 'wp_ajax_clearcache', array($this,'clearcache' ));
		
				
		add_action('wp_ajax_exitlogin',array($this,'exitlogin'));
		//add_action('wp_ajax_wangeditorupload',array($this,'wangeditorupload'));
		
		
		add_action('wp_ajax_nopriv_user_login',array($this,'user_login'));
		add_action('wp_ajax_nopriv_user_reg',array($this,'user_reg'));
		add_action('wp_ajax_nopriv_user_find',array($this,'user_find'));
		add_action('wp_ajax_nopriv_user_resetpwd',array($this,'user_resetpwd'));
		add_action('wp_ajax_changepwd',array($this,'changepwd'));
		add_action('wp_ajax_uploadavatar',array($this,'uploadavatar'));
		add_action('wp_ajax_uploadcover',array($this,'uploadcover'));
		add_action('wp_ajax_changedisplayname',array($this,'changedisplayname'));
		
		
		/* 社交账号登录绑定处理 */
		add_action('wp_ajax_nopriv_social_auth',array($this,'social_auth'));
		add_action('wp_ajax_social_auth',array($this,'social_auth'));
		add_action('wp_ajax_unbind_login',array($this,'unbind_login'));
		
		add_action('wp_ajax_nopriv_user_nolike',array($this,'user_nolike'));
		add_action('wp_ajax_user_nolike',array($this,'user_nolike'));
		add_action('wp_ajax_user_like',array($this,'user_like'));
		add_action('wp_ajax_nopriv_user_like',array($this,'user_like'));
		add_action('wp_ajax_user_nocollection',array($this,'user_nocollection'));//取消收藏
		add_action('wp_ajax_user_collection',array($this,'user_collection'));//收藏
		add_action('wp_ajax_nopriv_user_collection',array($this,'user_collection'));
		
		//投稿
		add_action('wp_ajax_save_post',array($this,'save_post'));
		add_action('wp_ajax_delete_post',array($this,'delete_post'));
	}
	
	
	function clearcache(){	//清除缓存
		admin_clear_cache();
		Cache::clear(null);
		$url=[];
		array_push($url,home_url());
		//wp_remote_get(home_url());
		$args=['exclude'=>1];
		$catelist=get_categories($args);
		foreach($catelist as $v){
			//wp_remote_get(get_category_link($v->term_id));
			array_push($url,get_category_link($v->term_id));
		}
		 wp_send_json(['code'=>1,'msg'=>'缓存已经刷新','url'=>$url]);
	}
	
	
	function user_login(){
		$name=Req::post('name');
		$pwd=Req::post('pwd');
		$nonce=Req::post('nonce');
		$code=Req::post('code');
		if(!wp_verify_nonce($nonce,'login_action')){
			wp_send_json(['code'=>0,'msg'=>'请刷新页面重试']);
		}
		if(empty(Req::session('logincode'))||strtolower($code)!=strtolower(Req::session('logincode'))){
			Req::setSession('logincode',null);
			wp_send_json(['code'=>0,'msg'=>'验证码输入错误']);
		}
		Req::setSession('logincode',null);//清除验证码
		$auth=new Auth();
		$res=$auth->login($name,$pwd);
		if($res){
			wp_send_json(['code'=>1,'msg'=>'登录成功']);
		}else{
			wp_send_json(['code'=>0,'msg'=>'用户名和密码错误']);
		}
	}
	
	function user_reg(){
		$name=Req::post('name');
		$pwd=Req::post('pwd');
		$email=Req::post('email');
		$nonce=Req::post('nonce');
		$code=Req::post('code');
		if(!wp_verify_nonce($nonce,'reg_action')){
			wp_send_json(['code'=>0,'msg'=>'请刷新网页重试']);
		}
		if(empty(Req::session('regcode'))||strtolower($code)!=strtolower(Req::session('regcode'))){
			Req::setSession('regcode',null);
			wp_send_json(['code'=>0,'msg'=>'验证码输入错误']);
		}
		Req::setSession('regcode',null);//清除验证码
		if(!Val::isEmail($email)){
			wp_send_json(['code'=>0,'msg'=>'请输入正确的邮箱']);
		}
		$flag=get_user_by('login',$name);
		if($flag){
			wp_send_json(['code'=>0,'msg'=>'这个用户名已经注册']);
		}
		$flag=get_user_by('email',$email);
		if($flag){
			wp_send_json(['code'=>0,'msg'=>'这个邮箱已经注册']);
		}
		$auth=new Auth();
		$res=$auth->reg($name,$pwd,$email);
		if($res){
			wp_send_json(['code'=>1]);
		}else{
			wp_send_json(['code'=>0,'msg'=>'注册失败，请重试']);
		}
	}
	
	function user_find(){	//找回密码
		$email=Req::post('email');
		$nonce=Req::post('nonce');
		$code=Req::post('code');
		if(!Val::isEmail($email)){
			wp_send_json(['code'=>0,'msg'=>'请输入正确的邮箱地址']);
		}
		if(empty(Req::session('findcode'))||strtolower($code)!=strtolower(Req::session('findcode'))){
			Req::setSession('findcode',null);
			wp_send_json(['code'=>0,'msg'=>'验证码输入错误']);
		}
		Req::setSession('findcode',null);
		if(!wp_verify_nonce($nonce,'find_action')){
			wp_send_json(['code'=>0,'msg'=>'请刷新页面重试']);
		}
		$user=get_user_by('email',$email);
		$url=home_url('resetpwd');
		$url=add_query_arg(['email'=>urlencode($user->user_email),'pwd'=>urlencode($user->user_pass)],$url);
		if($user){
			$e=new \wpzt\code\Email();
			$res=$e->sendEmailResetPwd($email,$url);
			if(!$res){
				wp_send_json(['code'=>0,'msg'=>'邮件发送失败请重试']);
			}else{
				wp_send_json(['code'=>1,'msg'=>'重置密码链接已发您邮箱']);
			}
		}else{
			wp_send_json(['code'=>0,'msg'=>'这个邮箱不存在']);
		}
	}

	function user_resetpwd(){
		$email=Req::post('email');
		$pwd=Req::post('pwd');
		$reset_pwd=Req::post('reset_pwd');
		$reset_repwd=Req::post('reset_repwd');
		if(!Val::isEmail($email)){
			wp_send_json(['code'=>0,'msg'=>'邮箱格式错误']);
		}
		if($reset_pwd!=$reset_repwd){
			wp_send_json(['code'=>0,'msg'=>'两次输入的密码不同']);
		}
		$user=get_user_by('email',$email);
		if(!$user){
			wp_send_json(['code'=>0,'msg'=>'获取用户信息失败']);
		}
		if($user->user_pass!=$pwd){
			wp_send_json(['code'=>0,'msg'=>'重置链接失效']);
		}
		$user_data=[
					'ID'=>$user->ID,
					'user_pass'=>$reset_pwd
				];
			$result=wp_update_user($user_data);
		if(is_wp_error($result)){
			wp_send_json(['code'=>0,'msg'=>'重置密码失败，请重试']);
		}else{
			wp_send_json(['code'=>1,'msg'=>'重置密码成功']);
		}
	}

	function changepwd(){
		$oldpwd=Req::post('oldpwd');
		$newpwd=Req::post('newpwd');
		$renewpwd=Req::post('renewpwd');
			if($newpwd!=$renewpwd){
				wp_send_json(['code'=>0,'msg'=>'两次新密码输入不相同']);
			}
		$auth=new \wpzt\Auth();	
		$flag=$auth->changepwd($oldpwd,$newpwd);
		if($flag['code']==0){
			wp_send_json($flag);
		}
		wp_logout();
		wp_send_json($flag); 
	}

	function uploadavatar(){//头像上传
		$file=Req::files('avatar');
		if($file){
			$allow=['jpg','jpeg','png','gif'];
			$ext=array_pop(explode('.',$file['name']));
			if(!in_array($ext,$allow)){
				wp_send_json(['code'=>0,'msg'=>'上传文件格式必须为png,jpg,jpeg']);
			}
			$allowsize=500*1024;
			if($file['size']>$allowsize){
				wp_send_json(['code'=>0,'msg'=>'文件要小于500K']);
			}
			if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
		//	$upload_overrides = array( 'test_form' => false );
		//	$uploadfile=wp_handle_upload($file,$upload_overrides);
			$a_id=media_handle_upload('avatar',0);
			if(is_wp_error($a_id)){
				wp_send_json(['code'=>0,'msg'=>'文件上传出错了']);
			}
			$a_url=wp_get_attachment_image_src($a_id,'full')[0];
			global $current_user;
			$uid=$current_user->ID;
		//	update_user_meta($uid,'avatar',$uploadfile['url']);
			update_user_meta($uid,'avatar',$a_url);
			wp_send_json(['code'=>1]);
		}
	}
	
	function uploadcover(){
		$file=Req::files('cover');
		if($file){
			$allow=['jpg','jpeg','png','gif'];
			$ext=array_pop(explode('.',$file['name']));
			if(!in_array($ext,$allow)){
				wp_send_json(['code'=>0,'msg'=>'上传文件格式必须为png,jpg,jpeg']);
			}
			$allowsize=500*1024;
			if($file['size']>$allowsize){
				wp_send_json(['code'=>0,'msg'=>'文件要小于500K']);
			}
			if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
			$upload_overrides = array( 'test_form' => false );
			$uploadfile=wp_handle_upload($file,$upload_overrides);
			if(isset($uploadfile['error'])){
				wp_send_json(['code'=>0,'msg'=>$uploadfile['error']]);
			}
			global $current_user;
			$uid=$current_user->ID;
			update_user_meta($uid,'cover',$uploadfile['url']);
			wp_send_json(['code'=>1]);
		}
	}

	

	function changedisplayname(){//修改昵称
		$displayname=Req::post('displayname');
		if(empty($displayname)){
			wp_send_json(['code'=>0,'msg'=>'请填写昵称']);
		}
		$uid=get_current_user_id();
		if(!$uid){
			wp_send_json(['code'=>0,'msg'=>'获取用户信息失败']);
		}
		$arg=['ID'=>$uid,'display_name'=>$displayname];
		wp_update_user($arg);
		wp_send_json(['code'=>1,'msg'=>'昵称修改成功']);
	}
	
	/*  社交账号登录绑定   */
	function social_auth(){
		$type=Req::post('type');
		if(empty($type)){
			wp_send_json(['code'=>0,'msg'=>'获取登录类型信息失败']);
		}
		switch($type){
			case 'qq':
				if(!wpzt('open_qq_login')||empty(wpzt('qq_login_id'))||empty(wpzt('qq_login_key'))){
					wp_send_json(['code'=>0,'msg'=>'请打开并配置QQ登录']);
				}
				$qq=new \wpzt\oauth\Qq();
				$url=$qq->getUrl();
				break;
			case 'weibo':
				if(!wpzt('open_weibo_login')||empty(wpzt('weibo_login_id'))||empty(wpzt('weibo_login_key'))){
					wp_send_json(['code'=>0,'msg'=>'请打开并配置微博登录']);
				}
				$weibo=new \wpzt\oauth\Weibo();
				$url=$weibo->getUrl();
				break;
			case 'wechat':
				if(!wpzt('open_wechat_login')||empty(wpzt('wechat_login_id'))||empty(wpzt('wechat_login_key'))){
					wp_send_json(['code'=>0,'msg'=>'请打开并配置微信登录']);
				}
				$wechat=new \wpzt\oauth\Wechat();
				$url=$wechat->getUrl();
				break;
			default:
				$url='';
		}
		if(empty($url)){
			wp_send_json(['code'=>0,'url'=>'获取配置信息失败']);
		}else{
			wp_send_json(['code'=>1,'url'=>$url]);
		}
	}
	

	
	function exitlogin(){
		wp_logout();
		wp_send_json(['code'=>1,'msg'=>'退出成功']);
	}
	
	
	function unbind_login(){
		$type=Req::post('type');
		$uid=get_current_user_id();
		if(!$uid){
			wp_send_json(['code'=>0,'msg'=>'获取用户信息出错']);
		}
		$flag=delete_user_meta($uid,$type.'openid');
		if($flag){
			wp_send_json(['code'=>1,'msg'=>'绑定已解除']);
		}else{
			wp_send_json(['code'=>0,'msg'=>'请重试']);
		}
	}
	
	function user_nolike(){
		$pid=Req::post('pid');
		if(empty($pid)||!Val::isInt($pid)){
			wp_send_json(['code'=>0,'msg'=>'获取文章信息失败']);
		}
		$cookie=Req::cookie('user_like_'.$pid);
		if(!empty($cookie)){
			wp_send_json(['code'=>0,'msg'=>'已踩']);
		}
		
		$likes=get_post_meta($pid,'like',true);
		
		if(empty($likes)){
			$likes=['like'=>0,'nolike'=>1];
		}else{
			$likes['nolike']+=1;
		}
		setcookie('user_like_'.$pid,1,time()+86400);
		update_post_meta($pid,'like',$likes);
		wp_send_json(['code'=>1,'msg'=>'已踩']);
	}

	
	
	
	
	
	
	function user_like(){	//点赞
		$pid=Req::post('pid');
		if(empty($pid)||!Val::isInt($pid)){
			wp_send_json(['code'=>0,'msg'=>'获取文章信息失败']);
		}
		$cookie=Req::cookie('user_like_'.$pid);
		if(!empty($cookie)){
			wp_send_json(['code'=>0,'msg'=>'已赞']);
		}
		
		$likes=get_post_meta($pid,'like',true);
		if(empty($likes)){
			$likes=['like'=>1,'nolike'=>0];
		}else{
			$likes['like']+=1;
		}
		setcookie('user_like_'.$pid,1,time()+86400);
		update_post_meta($pid,'like',$likes);
		wp_send_json(['code'=>1,'msg'=>'已赞']);
	}
	
	function user_nocollection(){//取消收藏
		$uid=get_current_user_id();
		if(empty($uid)){
			wp_send_json(['code'=>0,'msg'=>'获取用户信息失败']);
		}
		$pid=intval(Req::post('pid'));
		if(empty($pid)){
			wp_send_json(['code'=>0,'msg'=>'获取文章信息失败']);
		}
		global $wpdb;
		$where['pid']=$pid;
		$where['uid']=$uid;
		$flag=$wpdb->delete("{$wpdb->prefix}wpzt_collection",$where);
		if($flag){
			wp_send_json(['code'=>1,'msg'=>'取消收藏成功']);
		}else{
			wp_send_json(['code'=>0,'msg'=>'取消收藏失败']);
		}
	}
	
	function user_collection(){	//收藏
		$uid=get_current_user_id();
		if(empty($uid)){
			wp_send_json(['code'=>2,'msg'=>'请登录后收藏']);
		}
		$pid=intval(Req::post('pid'));
		$col=intval(Req::post('col'));//1关注2取消关注
		if(empty($pid)){
			wp_send_json(['code'=>0,'msg'=>'获取文章信息失败']);
		}
		global $wpdb;
		if($col==1){
			$flag=$wpdb->get_row("select * from {$wpdb->prefix}wpzt_collection where uid={$uid} and pid={$pid}");
			if($flag){
				wp_send_json(['code'=>0,'msg'=>'已经收藏']);
			}
			$insert=[
				'uid'=>$uid,'pid'=>$pid,'time'=>time()
			];
			$wpdb->insert("{$wpdb->prefix}wpzt_collection",$insert);
			wp_send_json(['code'=>1,'msg'=>'收藏成功']);
		}
		if($col==2){
			$where['pid']=$pid;
			$where['uid']=$uid;
			$flag=$wpdb->delete("{$wpdb->prefix}wpzt_collection",$where);
			if($flag){
				wp_send_json(['code'=>1,'msg'=>'取消收藏成功']);
			}else{
				wp_send_json(['code'=>0,'msg'=>'取消收藏失败']);
			}
		}
	}
	
	function save_post(){	//保存文档
		if(empty($_POST)||!wp_verify_nonce($_POST['save_post_nonce_field'],'save_post_action')){
			wp_send_json(['code'=>0,'msg'=>'提交数据失败，请重试']);
		}
		$uid=get_current_user_id();
		if(empty($uid)){
			wp_send_json(['code'=>0,'msg'=>'获取用户信息失败']);
		}
		$user=wp_get_current_user();
		if(!user_can_sendpost($user)){
			wp_send_json(['code'=>0,'msg'=>'您没有投稿的权限']);
		}
		$post_status=Req::post('status');
		if($post_status=='publish'&&!wpzt('user_post_status')){
			wp_send_json(['code'=>0,'msg'=>'投稿没权限直接发布']);
		}
		$postid=Req::post('postid');
		$post_data=[
			"post_title"=>Req::post('title'),
			 'post_content'=>$_POST['post_content'],
			'post_category'=>$_POST['category'],
			'post_author'=>$uid,
			'post_status'=>$post_status	
		];
		if(empty($postid)){
			$res=wp_insert_post($post_data);
		}else{
			$post=get_post($postid);
			if($post->post_author!=$uid){
				wp_send_json(['code'=>0,'msg'=>'不是自己的文章，不能编辑']);
			}
			$post_data['ID']=$postid;
			$res=wp_update_post($post_data);
		}
		if(is_wp_error($res)||empty($res)){
			wp_send_json(['code'=>0,'msg'=>'文件提交失败请重试']);
		}
		wp_send_json(['code'=>1,'msg'=>'文章提交成功']);
		
	}
	
	function delete_post(){
		$pid=Req::post('pid');
		$uid=get_current_user_id();
		if(empty($uid)){
			wp_send_json(['code'=>0,'msg'=>'获取用户信息失败']);
		}
		$post=get_post($pid);
		// if($post->post_status=="publish"&&!current_user_can( 'manage_options' )){
			// wp_send_json(['code'=>0,'msg'=>'已经发布的文章请联系管理员删除']);
		// }
		if($post->post_author!=$uid){
			wp_send_json(['cdoe'=>0,'msg'=>'不能删除别人的文章']);
		}
		$flag=wp_delete_post($pid);
		if(empty($flag)){
			wp_send_json(['code'=>0,'msg'=>'删除失败请重试']);
		}else{
			wp_send_json(['code'=>1,'msg'=>'删除成功']);
		}
	}
	

	
}