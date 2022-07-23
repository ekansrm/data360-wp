<?php
ob_clean();
$font=WPZT_DIR.'/static/font/1.ttf';
$type=empty($_GET['type'])?'login':$_GET['type'];
$imagecode=new  wpzt\code\Captcha(120,46,4,$type,'',$font); 
Header("Content-type: image/GIF");
$imagecode->imageout();