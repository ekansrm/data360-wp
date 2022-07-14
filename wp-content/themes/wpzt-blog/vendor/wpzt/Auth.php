<?php
namespace wpzt;

class Auth{		//用户授权
	function __construct(){
		
	}
	
	public function reg($userlogin,$pwd,$email){	
			$newUserData = array(
				'ID'         => '',
				'user_login' => $userlogin,
				'user_pass'  => $pwd,
			    'user_email' => $email,
			    'role'       => get_option('default_role'),
			);
			 $user_id = wp_insert_user($newUserData);
			 if(is_wp_error($user_id)){
				 return false;
			 }else{
				 wp_set_auth_cookie($user_id, true, false);
				 wp_set_current_user($user_id);
				 do_action('wpzt_reg_in',$user_id);
				 return $user_id;
			 }
	}
	
	public function login($userlogin,$pwd){	//用户数据
			$login_data=[];
			$login_data['user_login']=$userlogin;
			$login_data['user_password']=$pwd;
			$login_data['remember']=true;
			$user=wp_signon($login_data, false);
			if(is_wp_error($user)){
				return false;
			}else{
				do_action('wpzt_login_in',$user->ID);
				return $user;
			}
	}
	
	public function changepwd($oldpwd,$newpwd){//@return 1 成功2原密码错误3修改失败
			global $current_user;
			$login_data['user_login']=$current_user->user_login;
			$login_data['user_password']=$oldpwd;
			$user_verify=wp_signon($login_data,false);
			if(is_wp_error($user_verify)){
				return $this->errormsg('原密码输入错误');//原密码
			}else{
					$user_data=[
					'ID'=>$current_user->ID,
					'user_pass'=>$newpwd
				];
				$result=wp_update_user($user_data);
				if(is_wp_error($result)){
					return $this->errormsg('密码修改失败');//密码修改失败
				}else{
					return $this->successmsg('密码修改成功');
				}
			}
			
	}
	
	public function findpwd($userlogin,$newpwd){
			$userinfo=get_user_by('login',$userlogin);
			if(!$userinfo){
				return $this->errormsg('用户名不存在');
			}
			$userid=$userinfo->ID;
			$user_data=[
				'ID'=>$userid,
				'user_pass'=>$newpwd
			];
			$result=wp_update_user($user_data);
			if(is_wp_error($result)){
				return $this->errormsg('密码找回失败');
			}else{
				return $this->successmsg('密码成功找回');
			}
	}
	
	public function errormsg($msg){
		return ['code'=>0,'msg'=>$msg];
	}
	
	public function successmsg($msg='操作成功'){
		return ['code'=>1,'msg'=>$msg];
	}
}