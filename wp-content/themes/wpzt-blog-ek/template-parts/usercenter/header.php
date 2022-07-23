<?php
	$uid=get_current_user_id();
	if(empty($uid)){
		wp_redirect(get_login_url());
	}
	get_header();
	global $current_user;
	$role_name=get_role_name(new WP_User($uid));
	
	add_js('letan');
	add_js('uc');
	add_css('login-style');
	
?>
<!-- 页面内容 --> 
	<div class='ucpage'>
	<div class='pagectn container'>
	<!-- 个人中心头部 --> 
	<div class='uctop'>
	<div class='uctop-tx'>
	<img id="top_avatar" src='<?php echo get_user_avatar($uid);?>' alt='头像'/>	
	</div>
	<div class='uctop-ctn'>
	<p class='ucname'><?php echo $current_user->display_name;?>
		<span class='ucname-puhy'><?php echo $role_name;?></span>
	
	</p>
	
	</div>
	</div>
	<!-- 个人中心出现错误 --> 
	<?php
		if(!empty($_GET['errormsg'])){
	?>
	<div class='ucerrow'>出现错误啦,<?php echo $_GET['errormsg'];?>~</div>
	<?php
		}
	?>
	<!-- 个人内容 --> 
	<div class='ucctn'>
		<div class='ucctn-nav'>
			<ul>
			<li><a href='<?php echo home_url('usercenter');?>' title='个人资料' <?php if($myscreen=='userinfo'){?>class='active'<?php }?>>个人资料</a></li>
			<li><a href='<?php echo home_url('mypostlist');?>' title='文章列表' <?php if($myscreen=='mypostlist'){?>class='active'<?php }?>>文章列表</a></li>
			<li><a href='<?php echo home_url('sendpost');?>' title='编辑文章' <?php if($myscreen=='sendpost'){?>class='active'<?php }?>>编辑文章</a></li>
			
			</ul>	
			</div>