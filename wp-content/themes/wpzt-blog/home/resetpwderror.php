<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo WPZT_CSS;?>/login-style.css">

    <title>链接失效</title>

  </head>
  <body>
  <div class="logrebg">
  <div class="login-logo"><a href="<?php echo home_url();?>" title="首页">
  <img src="<?php echo wpzt_img('logo');?>" alt="logo"/>
  </a></div>
<div class="login findpassword">
<h2>链接失效</h2>
<div class="registerbtn">此链接已失效，请返回<a href="<?php echo home_url('find_pwd');?>">找回密码</a>页面，再次获取链接。</div>
<div class="registerbtn">我已想起密码，去<a href="<?php echo home_url('login');?>">登录</a></div>
</div>
<div class="backhome"><a href="#">返回首页</a><span>|</span>技术支持：wpzt.net</div>
</div>
  </body>
</html>