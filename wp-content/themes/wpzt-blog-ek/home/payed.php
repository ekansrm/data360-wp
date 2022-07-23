<?php
	use \wpzt\form\Request as Req;
	$myscreen='payed';
	require_once locate_template('template-parts/usercenter/header.php');
	global $wpdb;
	$mypage=Req::get('mypage');
	$mypage=empty($mypage)?1:intval($mypage);
	$pagesize=$_GET['pageSize'];
	$pagesize=empty($pagesize)?20:intval($pagesize);
	$startnum=($mypage-1)*$pagesize;
	$count=$wpdb->get_var("select count(*) from {$wpdb->prefix}wpzt_order where status=1 and uid={$uid} and pid<>0");
	$query="select pid from {$wpdb->prefix}wpzt_order where status=1 and uid={$uid} and pid<>0 order by id desc limit {$startnum},{$pagesize}";
	$list=$wpdb->get_results($query);
	$paginate=new \wpzt\Pagination($count,$pagesize);
	$paginate->pagerCount=8;
	$paginate->prevText = '上一页';
	$paginate->nextText = '下一页';
	?>
	<div class='ucctn-ctn uc-collect'>
	<?php
		if(!empty($list)){
			foreach($list as $v){
				$post=get_post($v->pid);
	?>
	
	<div class='uccolt-item'>
		<a href='<?php echo get_permalink($v->pid);?>' title='<?php echo $post->post_title;?>'>
			<div class='uccolt-item-img'>
				<img src='<?php echo get_post_img(210,120);?>' alt='<?php echo $post->post_title;?>'/>
			</div>
			<h3><?php echo $post->post_title;?></h3>
		</a>
	<!----	<a onclick="delcollection(<?php $v->pid?>)" title='删除' class='uccolt-del'><i class='iconfont icon-error'></i></a>--->
	</div>
	<?php
			}
		}?>
	
	</div>
	<!-- 分页 --> 
	<?php
		if(!empty($list)){
	?>
	<div class='w-fy'>
		<div class='w-fylink'>
			<?php echo $paginate->links();?>
		</div>
	</div>
	<?php
		}
	?>
	</div>
	</div>
<?php
get_footer();