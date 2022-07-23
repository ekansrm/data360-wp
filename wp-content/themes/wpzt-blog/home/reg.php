<?php 
	$redirect_to=\wpzt\form\Request::get('redirect_to');
	if(!empty($redirect_to)){
		\wpzt\form\Request::setSession('redirect_to',$redirect_to);
	}else{
		$redirect_to=\wpzt\form\Request::session('redirect_to');
		$redirect_to=empty($redirect_to)?home_url('usercenter'):$redirect_to;
	}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script>
		var ajaxurl="<?php echo admin_url('admin-ajax.php');?>";
	</script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo WPZT_CSS;?>/login-style.css">

    <title>注册页面</title>

  </head>
  <body>
  <div class="logrebg">
  <div class="login-logo"><a href="<?php echo home_url();?>" title="首页">
  <img src="<?php echo wpzt_img('logo');?>" alt="logo"/>
  </a></div>
<div class="login register">
<h2>欢迎注册</h2>
<form id="reg_form">
<input type="hidden" id="redirect_to" value="<?php echo $redirect_to;?>"/>
<?php wp_nonce_field("reg_action","reg_action_field");?>
<div>
<label>设置账号：</label>
<input type="text" name="reg_name" id="reg_name" placeholder="请输入用户名"></input>
</div>
<div>
<label>邮箱：</label>
<input type="text" name="reg_email" id="reg_email" placeholder="请输入邮箱地址"></input>
</div>
<div>
<label>密码：</label>
<input type="password" name="reg_pwd" id="reg_pwd" placeholder="请输入密码"></input>
</div>
<div>
<label>确认密码：</label>
<input type="password" name="reg_repwd" id="reg_repwd" placeholder="请再次输入密码"></input>
</div>
<div class="hasyzm">
	<label>验证码：</label>
	<input type="text" name="reg_code" id="reg_code" placeholder="请输入验证码"></input>
	<img src="<?php echo add_query_arg(['type'=>'reg'],home_url('img')); ?>" onclick="this.src='<?php echo add_query_arg(['type'=>'reg'],home_url('img')); ?>&s='+Math.random()" alt="验证码">
	</div>
<!--<div class="register-xy">
<input id="color-input-red" class="chat-button-location-radio-input" type="checkbox" name="color-input-red" value="#ddd"/>
	<label  for="color-input-red"></label>
	<span>我已阅读并同意<a href="#"  title="服务协议">《服务协议》</a></span>
	</div>-->
<button type="button" onclick="reg()">注&nbsp;&nbsp;&nbsp;&nbsp;册</button>
</form>
<div class="registerbtn">已有账号，去<a href="<?php echo home_url('login');?>">登录</a></div>
</div>
<div class="backhome"><a href="<?php echo home_url();?>">返回首页</a><span>|</span>技术支持：wpzt.net</div>
</div>

<script src="<?php echo WPZT_JS;?>/jquery2.1.1.min.js"></script>
<script src="<?php echo WPZT_JS?>/letan.js"></script>
<script src="<?php echo WPZT_JS?>/jquery.validate.js"></script>
<script src="<?php echo WPZT_JS;?>/login.js"></script>
  </body>
</html>