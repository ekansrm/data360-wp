<?php	//个人信息
	$myscreen='userinfo';
	require_once locate_template('template-parts/usercenter/header.php');
	
	
?>

	<div class='ucctn-ctn uc-pslinfo'>
	<div class='ucctn-title'>
	<h3>修改资料</h3>
	<p>修改您的个人账户信息</p>
	</div>
	<div class='pslinfo'>
	<form>
	  <div class='form-group'>
		<label>昵称<span>（昵称字数限制10个以内，超出可能显示错误。）</span></label>
		<input type='text' class='form-control' id="displayname" name='name' placeholder='请输入新昵称' value="<?php echo $current_user->display_name;?>">
	  </div>
	  <button type="button" onclick="change_displayname()" class='btn btn-primary wp-btn'>昵称修改</button>
	</form>
	
	<div class='changetx-f'>
	<div class='changetx'>
	<img id="avatarimg" src='<?php echo get_user_avatar($uid);?>' alt='头像' class='txlager'>
	<input type='file' onchange="changeavatar()" id="avatar" name='pic' accept='image/*' class='filepic'>
	<span>点击上传</span>
	</div>
	<div class='ghtx'>点击图片上传即可更换头像</div>	
	</div>
	
	</div>
	
	<div class='pslinfo'>
	<form>
	  <div class='form-group wp-form-group'>
		<label>修改密码<span> （重新设置登录密码，不少于6个字符）</span></label>
		<input type='password' class='form-control' id="oldpwd" name='name' placeholder='请输入当前密码'  >
	  </div>
		<div class='form-group wp-form-group'>
		<input type='password' class='form-control' id="newpwd" name='name' placeholder='请输入新密码'  >
		
	  </div>
		<div class='form-group wp-form-group'>
		<input type='password' class='form-control' id="renewpwd" name='name' placeholder='请再次确认新密码'  >
	  </div>
	  
	  <button type='button' onclick="changepwd()" class='btn btn-primary wp-btn'>修改密码</button>
	</form>
	</div>
	<?php
		if(wpzt('open_wechat_login')||wpzt('open_qq_login')||wpzt('open_weibo_login')){
	?>
	<div class='pslinfo pslinfo-sq'>
	 <div class='form-group wp-form-group'>
		<label>第三方授权<span> （授权第三方账号用于直接登录观看视频）</span></label>
		<div class='pslinfo-sfsq'>
		
				<?php
					 if(wpzt('open_wechat_login')){
						 $wechatbind=get_user_meta($uid,'wechatopenid',true);
						 if(empty($wechatbind)){
				?>
				
				<div><i class="iconfont icon-weixin" ></i><a onclick="social_login('wechat')" title="微信授权">微信授权</a></div>
						 <?php }else{?>
				<div><i class="iconfont icon-weixin" ></i><a onclick="unbind_login('wechat')" title="微信授权">解除微信授权</a></div>
				<?php
						 }
					 }
				?>
				<?php
					if(wpzt('open_qq_login')){
						$qqbind=get_user_meta($uid,'qqopenid',true);
						if(empty($qqbind)){
				?>
				<div><i class="iconfont icon-qq" ></i><a onclick="social_login('qq')" title="授权QQ">授权QQ</a></div>
						<?php }else{?>
				<div><i class="iconfont icon-qq"></i><a onclick="unbind_login('qq')" title="授权QQ">解除QQ授权</a></div>		
				<?php 
						}
					}
				?>
				<?php
					if(wpzt('open_weibo_login')){
						$weibobind=get_user_meta($uid,'weiboopenid',true);
						if(empty($weibobind)){
				?>
				<div><i class="iconfont icon-weibo" ></i><a onclick="social_login('weibo')" title="授权微博">授权微博</a></div>
						<?php }else{ ?>
				<div><i class="iconfont icon-weibo"></i><a onclick="unbind_login('weibo')" title="授权微博">解除微博授权</a></div>		
				<?php
						}
					}
				?>
		
	 </div>
	 </div>
	</div>
	<?php }?>
	
	</div>
	</div>
	</div>
	
<?php

get_footer();