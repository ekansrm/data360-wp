<?php
namespace wpzt\code;

class Email{
	function __construct(){
		add_action('phpmailer_init', array($this,'configsmtp'));
	}
	
	function configsmtp($phpmailer){	//配置SMTP
		$phpmailer->FromName = wpzt('email_fromname'); // 发件人昵称
    	$phpmailer->Host = wpzt('email_host'); // 邮箱SMTP服务器smtp.qq.com
    	$phpmailer->Port = 465; // SMTP端口，不需要改
    	$phpmailer->Username = wpzt('email_username'); // 邮箱账户zzuli@qq.com
    	$phpmailer->Password = wpzt('email_password'); // 此处填写邮箱生成的授权码，不是邮箱登录密码
    	$phpmailer->From = wpzt('email_username'); // 发件邮箱账户
    	$phpmailer->SMTPAuth = true;
    	$phpmailer->SMTPSecure = 'ssl'; // 端口25时 留空，465时 ssl，不需要改
    	$phpmailer->IsSMTP();
	}
	
	function sendEmailCode($to,$key='emailcode'){
		 $smscode=$this->getCode();
		 session_start();
		 $_SESSION[$key]=$smscode;
		 $email_fromname=wpzt('email_fromname');
		 $time=date('Y-m-d H:i:s',time());
		 $title=$email_fromname."-验证码";
		 $header=array('Content-Type: text/html; charset=UTF-8');
		 $content="<div style='padding-left:100px;'><p style='font-weight:500;font-size:20px;'>您好:</p>
				   <p style='font-size:16px;font-weight:300;padding-left:35px;'>您的验证码是&nbsp;&nbsp;&nbsp;&nbsp;<b style='font-size:18px;font-weight:900;'>{$smscode}</b>&nbsp;&nbsp;&nbsp;&nbsp;,请妥善保管。</p>
				   <p style='font-size:20px;font-weight:500;text-align:right;width:600px;'>{$email_fromname}</p>
				   <p style='font-size:16px;font-weight:300;text-align:right;width:600px;'>{$time}</p></div>";
				return  wp_mail($to,$title,$content,$header);
	}
	
	
	function sendEmailResetPwd($to,$url){
		 $email_fromname=wpzt('email_fromname');
		 $time=date('Y-m-d H:i:s',time());
		 $title=get_bloginfo('name').'重置密码';
		 $header=array('Content-Type: text/html; charset=UTF-8');
		 $content="<div style='padding-left:100px;'><p style='font-weight:500;font-size:20px;'>您好:</p>
				   <p style='font-size:16px;font-weight:300;padding-left:35px;'>您的重置链接是&nbsp;&nbsp;&nbsp;&nbsp;<b style='font-size:18px;font-weight:900;'>{$url}</b>&nbsp;&nbsp;&nbsp;&nbsp;,点击链接重置密码，如不跳转复制链接在浏览器打开重重密码。</p>
				   <p style='font-size:20px;font-weight:500;text-align:right;width:600px;'>{$email_fromname}</p>
				   <p style='font-size:16px;font-weight:300;text-align:right;width:600px;'>{$time}</p></div>";
				return wp_mail($to,$title,$content,$header);
	}
	
	
	private function getCode(){
		$smscode=substr(strval(rand(1000000,9999999)),1);
		return $smscode;
	}
	
	private function getPwd(){
		$pwd=substr(md5(rand(10000000,99999999)),3,10);
		return $pwd;
	}
	
	function sendEmailPwd($to){	//发送邮件修改密码
		 $pwd=$this->getPwd();
		 $user=get_user_by('email',$to);
			if(!$user){
				return false;
			}
			$user_data=[
						'ID'=>$user->ID,
						'user_pass'=>$pwd
					];
			$result=wp_update_user($user_data);
			if(is_wp_error($result)){
				return false;
			}
		 $email_fromname=wpzt('email_fromname');
		 $time=date('Y-m-d H:i:s',time());
		 $title=$email_fromname."-密码找回";
		 $header=array('Content-Type: text/html; charset=UTF-8');
		 $content="<div style='padding-left:100px;'><p style='font-weight:500;font-size:20px;'>您好:</p>
				   <p style='font-size:16px;font-weight:300;padding-left:35px;'>您的新密码是&nbsp;&nbsp;&nbsp;&nbsp;<b style='font-size:18px;font-weight:900;'>{$pwd}</b>&nbsp;&nbsp;&nbsp;&nbsp;,请妥善保管或修改。</p>
				   <p style='font-size:20px;font-weight:500;text-align:right;width:600px;'>{$email_fromname}</p>
				   <p style='font-size:16px;font-weight:300;text-align:right;width:600px;'>{$time}</p></div>";
		return  wp_mail($to,$title,$content,$header);
	}
}