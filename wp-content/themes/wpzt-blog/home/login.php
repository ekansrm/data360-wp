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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>登录页面</title>
	
	<script>
		var ajaxurl="<?php echo admin_url('admin-ajax.php');?>";
	</script>
	 <link rel="stylesheet" href="<?php echo WPZT_CSS?>/login-style.css"/>
  </head>
  <body>
		<div class="logrebg">
			<div class="login-logo">
				<a href="<?php echo home_url();?>" title="首页">
					<img src="<?php echo wpzt_img('logo');?>" alt="logo"/>
				</a>
			 </div>
			<div class="login">
				<h2>欢迎登录</h2>
				<form id="login-form">
				<input type="hidden" id="redirect_to" value="<?php echo $redirect_to;?>"/>
				<?php  wp_nonce_field('login_action','login_action_field');?>
					<div>
						<label>账号：</label>
						<input type="text" name="login_name" id="login_name" placeholder="请输入用户名"></input>
					</div>
					<div>
						<label>密码：</label>
						<input type="password" name="login_pwd" id="login_pwd" placeholder="请输入密码"></input>
					</div>
					<div class="hasyzm">
						<label>验证码：</label>
						<input type="text" name="login_code" id="login_code" placeholder="请输入验证码"></input>
						<img src="<?php echo add_query_arg(['type'=>'login'],home_url('img')); ?>" onclick="this.src='<?php echo add_query_arg(['type'=>'login'],home_url('img')); ?>&s='+Math.random()" alt="验证码">
					</div>
					<p><a href="<?php echo home_url('findpwd');?>" title="忘记密码">忘记密码</a></p>
					<button type="button" onclick="login()" class="mian-bgcolor"> 登&nbsp;&nbsp;&nbsp;&nbsp;录</button>
				</form>
				<div class="registerbtn">没有账号？<a href="<?php echo home_url('reg');?>">立即注册</a></div>
				<div class="login-dsf">
				<?php if(wpzt('open_qq_login')){?>
				<a onclick="social_login('qq')" title="QQ登录"><img src="<?php echo WPZT_IMG;?>/qq.png"></a>
				<?php }
					if(wpzt('open_wechat_login')){
				?>
				<a onclick="social_login('wechat')" title="微信登录"><img src="<?php echo WPZT_IMG;?>/wechatt.png"></a>
				<?php
					}
					if(wpzt('open_weibo_login')){
				?>
				<a onclick="social_login('weibo')" title="微博登录"><img src="<?php echo WPZT_IMG;?>/weibo.png"></a>
					<?php }?>
				</div>
					
			</div>
			<div class="backhome"><a href="<?php echo home_url();?>">返回首页</a><span>|</span>技术支持：wpzt.net</div>
		</div>
		<?php wp_footer();?>
		
		<script src="<?php echo WPZT_JS?>/jquery2.1.1.min.js"></script>
		<script src="<?php echo WPZT_JS?>/letan.js"></script>
		<script src="<?php echo WPZT_JS?>/jquery.validate.js"></script>
		<script src="<?php echo WPZT_JS;?>/login.js"></script>
  </body>
</html>