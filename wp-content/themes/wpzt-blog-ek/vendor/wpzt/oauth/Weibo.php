<?php
namespace wpzt\oauth;

class Weibo{//微博登录
	private $weibo_login_id;
	private $weibo_login_key;
	private $callbackurl;
	function __construct(){
		$this->weibo_login_id=wpzt('weibo_login_id');
	    $this->weibo_login_key=wpzt('weibo_login_key');
		$this->callbackurl=urldecode(home_url('logincallback'));
	}
	
	function getUrl(){
		$state=$this->getState();
		$_SESSION ['weibo_state'] =$state;
		if(wp_is_mobile()){
			$url = "https://open.weibo.cn/oauth2/authorize?client_id={$this->weibo_login_id}&display=mobile&response_type=code&redirect_uri={$this->callbackurl}&state={$state}";
		}else{
			$url = "https://api.weibo.com/oauth2/authorize?client_id={$this->weibo_login_id}&response_type=code&redirect_uri={$this->callbackurl}&state={$state}";
		}
		return $url;
	}
	
	function getState(){
		return  md5 ( uniqid ( mt_rand (1,9999999), true ) );
	}
	
	function wb_get_access_token($code){
		$url = "https://api.weibo.com/oauth2/access_token";
		$data = array('client_id' => $this->weibo_login_id,
			'client_secret' => $this->weibo_login_key,
			'grant_type' => 'authorization_code',
			'redirect_uri' => $this->callbackurl,
			'code' => $code);
		$response = wp_remote_post( $url, array(
				'method' => 'POST',
				'body' => $data,
			)
		);
		$output = json_decode($response['body'],true);
		return $output;
	}
	
	function oauth(){
		if(!isset($_GET['code'])) wp_die('code empty.');
		$code = $_GET['code'];
		$output = $this->wb_get_access_token($code);
		 $sina_access_token = $output['access_token'];
		$sina_uid = $output['uid'];
		if(empty($sina_uid)){
			wp_die('服务器响应错误.');
		}
		 $get_user_info_url = "https://api.weibo.com/2/users/show.json?uid={$sina_uid}&access_token={$sina_access_token}";
		 $usersina = wp_remote_get( $get_user_info_url );
		 $userinfo  = json_decode($usersina['body'] , true);
		 return [
			'openid'=>$sina_uid,
			'avatar'=>$userinfo['profile_image_url'],
			'nickname'=>$userinfo['screen_name']
		 ];
	}
	
	
}