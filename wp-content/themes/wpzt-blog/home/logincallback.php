<?php
use wpzt\oauth\Qq;
use wpzt\oauth\Wechat;
use wpzt\oauth\Weibo;
use wpzt\OAuth;
use wpzt\form\Request as Req;

$state=Req::get('state');
$qqstate=Req::session('qq_state');
$wechatstate=Req::session('wechat_state');
$weibostate=Req::session('weibo_state');
$loginurl=home_url('login');
if(empty($state)){
	wp_die("获取state失败，请<a href='{$loginurl}'>重试</a>");
}
if($state==$qqstate){	//QQ授权
	$qq=new Qq();
	$userinfo=$qq->oauth();
	$oauth=new OAuth($userinfo,'qq');
}elseif($state==$wechatstate){//微信授权
	$wechat=new Wechat();
	$userinfo=$wechat->oauth();
	$oauth=new OAuth($userinfo,'wechat');
	
}elseif($state==$weibostate){//微博授权
	$weibo=new Weibo();
	$userinfo=$weibo->oauth();
	$oauth=new OAuth($userinfo,'weibo');
}else{
	wp_die("state验证失败，请<a href='{$loginurl}'>重试</a>");
}
	$ecode=$oauth->exec_user();
	$oauth->redirect_url($ecode);
