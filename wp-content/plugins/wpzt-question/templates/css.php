<?php
	$color=wpzt_pq('color');
?>
<style>
.qapage a:hover,.linkhf-a a.active,.linkhf-a a:hover,.linkhf-btn a,.qapage-aqul li a.aqtitle {color:<?php echo $color;?>!important;}
.linkhf-btn a {border:1px <?php echo $color;?> solid!important;}
.linkhf-a a.active:after,.linkhf-a a:hover:after,.twbtn,.form-submit,.comment-inner button.reply-btn {background:<?php echo $color;?>!important;}
.qapage-sidebar-title h3,.comment-title h3 {border-left:3px <?php echo $color;?> solid!important;}
.qapage-linkhf a:hover {color:#fff!important;background:<?php echo $color;?>!important;}
.w-fylink a.active,.w-fylink a.active, .w-fylink a.active:hover,.btn-primary{border-color: <?php echo $color;?>!important; background-color: <?php echo $color;?>!important;}
.w-fylink a.active:hover,.btn-primary{color:#fff!important;}

<?php
if(wpzt_pq('yuanjiao')){
?>
.qapage-article,.qapage-sidebar-1,.qapage-link,.qapage-top{border-radius:10px;overflow:hidden;}
<?php
}
?>
</style>