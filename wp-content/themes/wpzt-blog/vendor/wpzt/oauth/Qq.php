<?php
namespace wpzt\oauth;

class Qq{	//qq登录
	private $appkey;
	private $appsecret;
	private $callbackurl;
	function __construct(){
		$this->appkey=wpzt('qq_login_id');
		$this->appsecret=wpzt('qq_login_key');
		$this->callbackurl=urlencode(home_url('logincallback'));
	}
	
	function getUrl(){
		 $state=$this->getState();
		 $_SESSION ['qq_state']=$state;
		 $url = "https://graph.qq.com/oauth2.0/authorize?client_id={$this->appkey}&state={$state}&response_type=code&redirect_uri={$this->callbackurl}";
		 return $url;
	}
	
	function getState(){
		return  md5 ( uniqid ( mt_rand (1,99999999), true ));
	}
	
	function oauth(){
			$code = $_GET['code'];
			$token_url = "https://graph.qq.com/oauth2.0/token?client_id={$this->appkey}&client_secret={$this->appsecret}&grant_type=authorization_code&redirect_uri={$this->callbackurl}&code={$code}";
			$response = wp_remote_get( $token_url );
			if (is_wp_error($response)) {
				die($response->get_error_message());
			}
			$response = $response['body'];
			if (strpos ( $response, "callback" ) !== false) {
				wp_redirect(home_url());
			}
			$params = array ();
			parse_str ( $response, $params );
			$qq_access_token = $params ["access_token"];
			$graph_url = "https://graph.qq.com/oauth2.0/me?access_token={$qq_access_token}";
			$str = wp_remote_get( $graph_url );
			$str = $str['body'];
			if (strpos ( $str, "callback" ) !== false) {
				$lpos = strpos ( $str, "(" );
				$rpos = strrpos ( $str, ")" );
				$str = substr ( $str, $lpos + 1, $rpos - $lpos - 1 );
			}
			$user = json_decode ( $str,true );
			if (isset ( $user->error )) {
				echo "<h3>错误代码:</h3>" . $user->error;
				echo "<h3>信息  :</h3>" . $user->error_description;
				exit ();
			}
			$qq_openid = $user['openid'];
			if(!$qq_openid){
				wp_redirect(home_url());
				exit;
			}
			$get_user_info = "https://graph.qq.com/user/get_user_info?access_token={$qq_access_token}&oauth_consumer_key={$this->appkey}&openid={$qq_openid}&format=json";
			$data = wp_remote_get( $get_user_info );
			$data = $data['body'];
			$data  = json_decode($data , true);
			return [
				'openid'=>$qq_openid,
				'nickname'=>$data['nickname'],
				'avatar'=> $data['figureurl_qq']
			];
		}
}