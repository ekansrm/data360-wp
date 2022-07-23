<?php
namespace wpzt\oauth;

class Wechat{//微信登录
	private $wechat_login_id;
	private $wechat_login_key;
	private $callbackurl;
	function __construct(){
		$this->wechat_login_id=wpzt('wechat_login_id');
		$this->wechat_login_key=wpzt('wechat_login_key');
		$this->callbackurl=urlencode(home_url('logincallback'));
	}
	
	function getUrl(){
			$state=$this->getState();
			$_SESSION ['wechat_state'] =$state;
			$url = "https://open.weixin.qq.com/connect/qrconnect?appid={$this->wechat_login_id}&redirect_uri={$this->callbackurl}&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
			
			return $url;
	}
	
	function getState(){
		return  md5 ( uniqid ( mt_rand (1,9999999), true ) );
	}
	
	function get_wechat_access_token( $code = null ){
		$code = $code ? $code : $_GET['code'];
	    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->wechat_login_id}&secret={$this->wechat_login_key}&code={$code}&grant_type=authorization_code";
		return json_decode(file_get_contents($url),true);
	}
	
	function oauth(){
		 if(!isset($_GET['code'])) wp_die('code empty.');
		 $code = $_GET['code'];
		 $json_token = $this->get_wechat_access_token( $code );
		 $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$json_token['access_token']}&openid={$json_token['openid']}";
		 $user_info = json_decode(file_get_contents($info_url),true);
		// $weixin_id = $user_info['openid'];
		 if(empty($user_info['openid'])){ wp_die('授权时发生错误');}
		 return [
			'openid'=>$user_info['openid'],
			'avatar'=>$user_info['headimgurl'],
			'nickname'=>$user_info['nickname']
		 ];
	}
}
