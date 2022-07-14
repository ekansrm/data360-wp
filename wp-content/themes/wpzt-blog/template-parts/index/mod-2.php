<?php
	$cat1=$mod['index-mod-2-cat1'];
	$cat2=$mod['index-mod-2-cat2'];
	$cat3=$mod['index-mod-2-cat3'];
	$args1=['cat'=>$cat1,'posts_per_page'=>6];
	$args2=['cat'=>$cat2,'posts_per_page'=>6];
	$args3=['cat'=>$cat3,'posts_per_page'=>6];
	$attr=['width'=>227,'height'=>125,'meta'=>false];
	$plist1=get_post_array($args1,$attr);
	$plist2=get_post_array($args2,$attr);
	$plist3=get_post_array($args3,$attr);
?>
<div class='homebk1 homebk'>
		<div class='homebk-title'>
		<h2><span><?php echo $mod['title'];?></span></h2>
		</div>
		<div class='homebk1-ctn'>
		
		<?php
			if(!empty($plist1)){
				$p1=$plist1[0];
		?>
		<div class='homebk1-item'>
		<div class='homebk1-img position-r'>
		<a href='<?php echo $p1['link'];?>' title='<?php echo $p1['title'];?>'>
		<img src='<?php echo $p1['img'];?>' alt='<?php echo $p1['title'];?>'>
		<h3><?php echo $p1['title'];?></h3>
		</a>
		</div>
		<ul>
		<?php
			foreach($plist1 as $k=>$v){
				if($k==0){continue;}
		?>
		<li><a href='<?php echo $v['link'];?>' title='<?php echo $v['title'];?>'><?php echo $v['title'];?></a></li>
		<?php
			}
		?>
		</ul>
		</div>
		<?php
			}?>
		<?php
			if(!empty($plist2)){
				$p1=$plist2[0];
		?>
		<div class='homebk1-item'>
		<div class='homebk1-img position-r'>
		<a href='<?php echo $p1['link'];?>' title='<?php echo $p1['title'];?>'>
		<img src='<?php echo $p1['img'];?>' alt='<?php echo $p1['title'];?>'>
		<h3><?php echo $p1['title'];?></h3>
		</a>
		</div>
		<ul>
		<?php
			foreach($plist2 as $k=>$v){
				if($k==0){continue;}
		?>
		<li><a href='<?php echo $v['link'];?>' title='<?php echo $v['title'];?>'><?php echo $v['title'];?></a></li>
		<?php
			}?>
		</ul>
		
		</div>
		<?php
			}?>
		<?php
			if(!empty($plist3)){
				$p1=$plist3[0];
		?>
		<div class='homebk1-item'>
		<div class='homebk1-img position-r'>
		<a href='<?php echo $p1['link'];?>' title='<?php echo $p1['title'];?>'>
		<img src='<?php echo $p1['img'];?>' alt='<?php echo $p1['title'];?>'>
		<h3><?php echo $p1['title'];?></h3>
		</a>
		</div>
		<ul>
		<?php
			foreach($plist3 as $k=>$v){
				if($k==0){continue;}
		?>
		<li><a href='<?php echo $v['link'];?>' title='<?php echo $v['title'];?>'><?php echo $v['title'];?></a></li>
		<?php
			}?>
		</ul>
		
		</div>
		<?php
			}?>
		
		</div>
	</div>