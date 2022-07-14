<?php
	get_header();
?>
<style>
html,body{height:100%;}
.logrebg{min-height:100%;}
.nopage{text-align:center;padding-top:110px;min-height:100%;padding-bottom:84px;}
.nopage img{width:200px; height:auto;}
.nopage p{font-size:16px;color:#aaa;margin-top:30px;}
.nopage a{font-size:18px;font-family:FZCQJW--GB1-0;line-height:28px;color:rgba(36,100,155,1);border:1px solid rgba(36,100,155,1);border-radius:20px;padding:6px 25px;text-decoration:none;margin-top:30px;display:block;margin-left:auto;margin-right:auto;width:150px;}
.nopage a:hover{background:rgba(36,100,155,0.8);color:#fff;}
footer{position:absolute;bottom:0;left:0;right:0;}
@media screen and (max-width: 414px){
	.logrebg{min-height:100%;}
	.nopage{padding:100px 15px;}
	.nopage img{width:160px; height:auto;}
	.m-footer{position:absolute;bottom:0;left:0;right:0;}
}
</style>
 <div class="logrebg">
  <div class="container nopage">
	<img src="<?php echo WPZT_IMG;?>/img404-1.png" alt="页面丢失">
	<p>很抱歉，暂时找不到这个页面，如有任何问题请联系下方工作人员。</p>
	<a href="<?php echo home_url();?>">返回首页</a>
	</div>
	</div>

<?php 
get_footer();