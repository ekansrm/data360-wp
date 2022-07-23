<?php
namespace wpzt;

class OAuth{
	private $openid;
	//private $userinfo;
	private $avatar;
	private $nickname;
	private $uid;
	private $type;//登录方式qq,wechat,weibo
	function __construct($userinfo,$type){
		$this->openid=$userinfo['openid'];
		$this->nickname=$userinfo['nickname'];
		$this->avatar=$userinfo['avatar'];
		$this->type=$type;		
		$this->uid=get_current_user_id();
	}
	public function exec_user(){
		global $wpdb;
		$query="select user_id from {$wpdb->prefix}usermeta where meta_key='{$this->type}openid' and meta_value='{$this->openid}'";
		$userid=$wpdb->get_var($query);
		if(empty($userid)){	//没有绑定
			if(empty($this->uid)){//注册操作
				return $this->reg();
			}else{		//绑定操作
				return $this->bind();
			}
		}else{				//已经绑定
			if(empty($this->uid)){//登录操作
				return $this->login($userid);
			}else{//判断ID与已登录的用户ID是否相同，相同已经绑定，不相同提示已绑定其他账号
				if($this->uid==$userid){
					return 'binded';
				}else{
					return 'binderror';
				}
			}
		}
	}
	
	private function reg(){
		$login_name =$this->type .date('ymdHis',time()) . mt_rand(10000, 99999);
        $user_pass  = md5(date('ymdHis',time()));
		//var_dump($this->userinfo);
        $nickname = $this->nickname;
		$avatar=$this->avatar;
        $userdata   = array(
            'user_login'   => $this->type.$this->openid,
            'user_email'   => $this->type.$this->openid.'@wpmee.com',
            'display_name' => $nickname,
            'nickname'     => $nickname,
            'user_pass'    => $user_pass,
			 'role'       => get_option('default_role'),
        );
        $user_id = wp_insert_user($userdata);
		if(is_wp_error($user_id)){
			return 'error';
		}
		update_user_meta($user_id,$this->type.'openid',$this->openid);
		update_user_meta($user_id,'avatar',$avatar);
		 wp_set_current_user($user_id);
         wp_set_auth_cookie($user_id);
         $user = get_user_by('id', $user_id);
         do_action('wp_login', $user->user_login, $user); 
		 return 'reg';
	}
	
	private function bind(){
		update_user_meta($this->uid,$this->type.'openid',$this->openid);
		return 'bind';
	}
	private function login($userid){
		 wp_set_current_user($userid);
         wp_set_auth_cookie($userid);
         $user = get_user_by('id', $userid);
         do_action('wp_login', $user->user_login, $user); // 保证挂载的action执行
		 return 'login';
	}
	 
	public function redirect_url($type){	//登录后的跳转
		$redirect_to=\wpzt\form\Request::session('redirect_to');
		if(empty($redirect_to)){
			$redirect_to=home_url('usercenter');
		}
		switch($type){
			case 'reg':wp_redirect($redirect_to);break;
			case 'login':wp_redirect($redirect_to);break;
			case 'bind':wp_redirect(home_url('usercenter'));break;
			case 'binded':wp_redirect(home_url('usercenter'));break;
			case 'binderror':wp_redirect(add_query_arg(['errormsg'=>'绑定出错了,已经其他账号了'],home_url('usercenter')));break;
			case 'error':wp_die("出错了，请<a href='{$loginurl}'>重试</a>");
		}
	}
}