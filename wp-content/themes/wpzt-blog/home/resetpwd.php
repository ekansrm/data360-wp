<?php
use wpzt\form\Request as Req;
use wpzt\form\Validate as Val;
$email=urldecode(Req::get('email'));
$pwd=urldecode(Req::get('pwd'));
if(!Val::isEmail($email)){
	wp_safe_redirect(home_url('resetpwderror'));exit;
}
$user=get_user_by('email',$email);
if(!$user){
	wp_safe_redirect(home_url('resetpwderror'));exit;
}
 if($user->user_pass!=$pwd){
	 wp_safe_redirect(home_url('resetpwderror'));exit;
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

    <title>设置新密码</title>

  </head>
  <body>
  <div class="logrebg">
  <div class="login-logo"><a href="<?php echo home_url();?>" title="首页">
  <img src="<?php echo wpzt_img('logo');?>" alt="logo"/>
  </a></div>
<div class="login findpassword">
<h2>设置新密码</h2>
<form id="resetpwd_form">
<input type="hidden" id="homeurl" value="<?php echo home_url();?>"/>
<input type="hidden" id="email" value="<?php echo $email;?>"/>
<input type="hidden" id="old_pwd" value="<?php echo $pwd;?>"/>
<?php wp_nonce_field("resetpwd_action","resetpwd_action_field");?>
<div>
<label>新密码：</label>
<input type="password" name="reset_pwd" id="reset_pwd" placeholder="请输入密码"></input>
</div>
<div>
<label>确认密码：</label>
<input type="password" name="reset_repwd" id="reset_repwd" placeholder="请再次输入密码"></input>
</div>
<button type="button" onclick="resetpwd()">确&nbsp;&nbsp;&nbsp;&nbsp;认</button>
</form>
<p class="findpassword-ts">提示：建议密码由字母和数字组成，区分大小写。</p>
</div>
<div class="backhome"><a href="<?php echo home_url();?>">返回首页</a><span>|</span>技术支持：wpzt.net</div>
</div>

<script src="<?php echo WPZT_JS;?>/jquery2.1.1.min.js"></script>
<script src="<?php echo WPZT_JS?>/letan.js"></script>
<script src="<?php echo WPZT_JS?>/jquery.validate.js"></script>
<script src="<?php echo WPZT_JS;?>/login.js"></script>

  </body>
</html>