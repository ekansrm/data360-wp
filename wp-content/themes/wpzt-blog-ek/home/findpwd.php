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
    <link rel="stylesheet" href="<?php echo WPZT_CSS?>/login-style.css">

    <title>找回密码</title>

  </head>
  <body>
		  <div class="logrebg">
			  <div class="login-logo">
				  <a href="<?php echo home_url();?>" title="首页">
					<img src="<?php echo wpzt_img('logo');?>" alt="logo"/>
				  </a>
			  </div>
			<div class="login findpassword">
				<h2>找回密码</h2>
					<form id="find_form">
					<?php wp_nonce_field('find_action','find_action_field');?>
						<div>
						<label>邮箱：</label>
						<input type="text" name="find_email" id="find_email" placeholder="请输入邮箱地址"></input>
						</div>
						<div class="hasyzm">
						<label>验证码：</label>
						<input type="text" name="find_code" id="find_code" placeholder="请输入验证码"></input>
						<img src="<?php echo add_query_arg(['type'=>'find'],home_url('img')); ?>" onclick="this.src='<?php echo add_query_arg(['type'=>'find'],home_url('img')); ?>&s='+Math.random()" alt="验证码">
						</div>
						<button type="button" onclick="find()">获取链接</button>
					</form>
				<p class="findpassword-ts">提示：点击获取新密码后，系统会向您发送重置密码的链接地址到您的注册邮箱，请注意查收。</p>
				<div class="registerbtn">我已想起密码，去<a href="<?php echo home_url('login');?>">登录</a></div>
			</div>
			<div class="backhome"><a href="<?php echo home_url();?>">返回首页</a><span>|</span>技术支持：wpzt.net</div>
		</div>
	
		<script src="<?php echo WPZT_JS;?>/jquery.min.js"></script>
		<script src="<?php echo WPZT_JS?>/letan.js"></script>
		<script src="<?php echo WPZT_JS?>/jquery.validate.js"></script>
		<script src="<?php echo WPZT_JS;?>/login.js"></script>
  </body>
</html>