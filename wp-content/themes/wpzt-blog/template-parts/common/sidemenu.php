 <?php
	$sidemenu=get_menu_array('side');
	if(!empty($sidemenu)){
 ?>
	 <div class='homebknav'>
		<ul>
		<?php
			foreach($sidemenu as $v){
					$img=empty($v['meta']['icon']['url'])?'':$v['meta']['icon']['url'];
				
		?>
		<li><a href='<?php echo $v['link'];?>' target="<?php echo $target?>" title='<?php echo $v['name'];?>' <?php if($v['link']==\wpzt\form\Request::url()){?> class='active'<?php }?>><?php if(!empty($img)){?><img src='<?php echo $img;?>' alt='<?php echo $v['name'];?>'><?php }?><?php echo $v['name'];?></a></li>
		<?php
			}?>
	
		</ul>
	 </div>
<?php
	}
?>