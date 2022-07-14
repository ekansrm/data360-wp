<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
<script>
	ajaxurl="<?php echo admin_url('admin-ajax.php');?>";
</script>
<?php if( wpzt_img('favicon','') ) { ?>
<link rel="shortcut icon" href="<?php echo wpzt_img('favicon','');?>"/>
<?php }else{ ?>
<link rel="shortcut icon" href="<?php echo WPZT_IMG;?>/favicon.ico"/>
<?php }?>
<?php wp_head(); ?>
<?php echo wpzt('head_code');?>
<?php add_js('jq21');add_js("bootstrap");add_js('swiper.min');add_js('stickysidebar');?>

<style>
<?php $maincolor=wpzt('main-color');?>
a:hover{color:<?php echo $maincolor;?>;}
.main-bg,.menunav .navul > li > a,.searchmod form button,.w-fylink a.active,.w-fylink a.active:hover,.w-wznr-body h2:before,.form-submit,.ucctn-nav ul li a:before,.wp-btn,.sendpost-btn,.w-fylink .current{background-color:<?php echo $maincolor;?>!important;}
.main-border,.w-fylink a.active,.comment-title h3 i,.w-fylink .current{border-color:<?php echo $maincolor;?>!important;}
.w-sidr-header,.entry-comments,.special-listitem{border-top-color:<?php echo $maincolor;?>;}
.homebk-title h2 span{border-bottom-color:<?php echo $maincolor;?>;}
.w-sdimg-wp:hover h4,.w-sdimg2:hover h4,.homebknav a.active,.w-wzhd-icon a:hover,.w-tjydby-r ul li a:hover,a.goinspecial,.menunav .sub-menu li:hover a,.comment-title h3 a{color:<?php echo $maincolor;?>!important;}
<?php if(wpzt('open-fillet')){?>
img{border-radius:8px;}
.homebk4-img,.homebk1-img,.homebk2-img,.w-sdimg-img2,.w-sdimg-img,.w-swiper,.onebigimg{border-radius:8px;overflow:hidden;}
<?php }?>
 
</style>
</head>
<body>
<div class="site-wrapper">

 <header id='header'>
	   <!--PC页头-->
        <div class='header-pc  mb-hid'>
		<div class='header-logosech container'>
            <div class='logo'>
			<h1>
              <a href='<?php echo home_url();?>' title='<?php echo get_bloginfo();?>'>
                <img src='<?php echo wpzt_img('logo');?>' title='logo' />
				</a>
			</h1>
            </div>
					<!--搜索框-->
		  <div class='searchmod'>
		  <form  class='search-form main-border' method="get" action="<?php echo home_url();?>">
			<input type='text' placeholder='请输入内容或关键字...' name='s'>
          <button type="submit">
            <i class='iconfont icon-search'></i>
          </button>
			</form>
		</div>
		<div class='hd-login'>
		<!--登录前-登录注册 -->
		<?php
			if(!is_user_logged_in()){
		?>
		<div class='login-a' >
		<div class='login-abtn'>
		<img src='<?php echo WPZT_IMG;?>/yonghu.png' alt='头像' class='logina-img'>
		<div class='logina-btn'>
		<div>
		<a href='<?php echo home_url('login');?>' rel="nofollow" title='登录' class='uca'>登录</a>		
		<a href='<?php echo home_url('reg');?>' rel="nofollow" title='注册' class='loginout'>注册</a>		
		</div>	
		</div>	
		</div>	
		</div>
		<?php
			}else{
			global $current_user;	
				?>
			
		<!-- 登录后-个人中心 -->
		<div class='login-a'>
		<div class='login-abtn'>
		<img src='<?php echo get_user_avatar($current_user->ID);?>' alt='头像' class='logina-img'>
		<div class='logina-btn'>
		<div>
		<a href='<?php echo home_url('usercenter');?>'  title='个人中心' class='uca'>个人中心</a>
		<?php
			
			if(user_can_sendpost($current_user)){
		?>
		<a href='<?php echo home_url('sendpost')?>' title='发布文章' class='uca'>发布文章</a>
		<a href='<?php echo home_url('mypostlist');?>' title='我的文章' class='uca'>我的文章</a>
		<?php }?>
		<a href='<?php echo wp_logout_url(home_url());?>' title='退出' class='loginout'>退出</a>		
		</div>	
		</div>	
		</div>	
		</div>
			<?php }?>
		</div>
		</div>
            <div class='menunav main-bg'>
              <nav class='container'>
                <ul class='navul'>
                 <?php wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'main')); ?> 
                </ul>
              </nav>
            </div>
			
        
        </div>
        <!--移动端导航-->
        <div class='header-m dis-none mb-has'>
		 <a class='mb-navbtn mbtoggler' href='javascript:0;'>
            <i class='iconfont icon-heng'></i>
          </a>
		  
		  
            <div class='logo '>
           <h1>
              <a href='<?php echo home_url();?>' title='<?php echo get_bloginfo();?>'>
                <img src='<?php echo wpzt_img('logo');?>' title='logo' /></a>
			</h1>                    
		  </div>
		  <div class='schcart-btn'>
		 <a class='mb-navbtn searchbtn' rel="nofollow" href='javascript:0;'>
            <i class='iconfont icon-search'></i>
          </a>
		  </div>
		  			<!--搜索框-->
		  <div class='searchmod'>
		  <form  class='search-form' method="get" action="<?php echo home_url();?>">
			<input type='text' placeholder='请输入内容或关键字...' name='s'>
          <button type="submit">
            <i class='iconfont icon-search'></i>
          </button>
			</form>
		</div>
          <!--导航-->
          <div class='mbnav'>	
		  <a href='javascript:0;' rel="nofollow" title='关闭' class='navclose'>
              <i class='iconfont icon-error'></i>
            </a>	
          <nav>		
		  <div class='mbnavuc'>
            <!--登录前-登录注册 -->
		<?php
			if(!is_user_logged_in()){
		?>
		<div>
			<img src='<?php echo WPZT_IMG;?>/yonghu.png' alt='头像' class='logina-img'>
			<a href='<?php echo home_url('login');?>' rel="nofollow" title='登录' class='uca'>登录</a>	or	
		<a href='<?php echo home_url('reg');?>' rel="nofollow" title='注册' class='loginout'>注册</a>
			</div>
			<?php
			}else{
			global $current_user;	
				?>
			<div>
			<img src='<?php echo get_user_avatar($current_user->ID);?>' alt='头像' class='logina-img'>
			<a href='<?php echo home_url('usercenter');?>'  title='个人中心' class='uca'>个人中心</a>or
			<a href='<?php echo wp_logout_url(home_url());?>' title='退出' class='loginout'>退出</a>		
			</div>
			<?php }?>
			
			</div>
			 <!--<div class='logo'>
			<h1>
              <a href='<?php echo home_url();?>' title='<?php echo get_bloginfo();?>'>
                <img src='<?php echo wpzt_img('logo');?>' title='logo' />
				</a>
			</h1>
            </div>-->
            <ul class='mbnavul'>
             <?php wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'main')); ?> 	
					
            </ul>
          </nav>
        </div>
        </div>
		
      </header>