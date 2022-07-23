<?php
	use wpzt\form\Request as Req;
	$username=Req::post('username');
	$ordersn=Req::post('ordersn');
	if(!empty($username)||!empty($ordersn)){//搜索
		
		$url=Req::url();//当前页面url
		$url=remove_query_arg(['username','ordersn','mypage','pageSize'],$url);
		if(!empty($username)){
			$url=add_query_arg(['username'=>$username],$url);
		}
		if(!empty($ordersn)){
			$url=add_query_arg(['ordersn'=>$ordersn],$url);
		}
		
		wp_safe_redirect($url);
		exit;
	}
	
	$username=Req::get('username');
	$ordersn=Req::get('ordersn');
	
	
	$mypage=Req::get('mypage');
	$mypage=empty($mypage)?1:intval($mypage);
	$pagesize=$_GET['pageSize'];
	$pagesize=empty($pagesize)?20:intval($pagesize);
	$countsearchstr='';
	$searchstr='';
	if(!empty($username)||!empty($ordersn)){
		//$mypage=1;
		if(!empty($username)){
			$searchstr.=" and u.user_login='{$username}'";
			$user=get_user_by('login',$username);
			$countsearchstr=" and uid={$user->ID}";
		}
		if(!empty($ordersn)){
			$searchstr.=" and o.sn='{$ordersn}'";
			$countsearchstr=" and sn='{$ordersn}'";
		}
	}
	
	global $wpdb;
	$count=$wpdb->get_var("select count(*) from {$wpdb->prefix}wpzt_order where   status=1 and type>=1 and type<=4 {$countsearchstr}");
	
	
	
	$startnum=($mypage-1)*$pagesize;
	
	// $query="select o.order_sn sn,p.post_title title,u.user_login user,o.time time from {$wpdb->prefix}order o,{$wpdb->prefix}posts p,
		// {$wpdb->prefix}users u where o.pid=p.id and o.uid=u.id and o.status=1 {$searchstr} ORDER BY o.id DESC limit {$startnum},{$pagesize}";
	
	$query="select o.sn sn,u.user_login user,o.time time,o.price price,o.type type from {$wpdb->prefix}wpzt_order o 
	 left join {$wpdb->prefix}users u on o.uid=u.id where o.status=1 and o.type>=1 and o.type<=4 {$searchstr} ORDER BY o.id DESC limit {$startnum},{$pagesize}";
		
	$list=$wpdb->get_results($query);
	$paginate=new \wpzt\Pagination($count,$pagesize);
	$paginate->pagerCount=8;
	$paginate->prevText = '上一页';
	$paginate->nextText = '下一页';
?>
<link href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo WPZT_CSS;?>/pagination.css">

<div style="margin:25px 15px;">
<form method="post" class="form-inline" action="<?php echo add_query_arg(home_url(add_query_arg()));?>">
 <label class="" for="username">用户</label>
    <input type="text" class="form-control" id="username" name="username" placeholder="请输入用户名">
 <label for="name">订单号</label>
	<input type="text" class="form-control" id="ordersn" name="ordersn" placeholder="订单号">
	<button type="submit" class="btn btn-default">搜索</button>
</form>
</div>


<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            已支付订单
        </h3>
    </div>
    <div class="panel-body">
	<?php
		if(!empty($list)){
	?>
        <table class="table table-hover">
	  <thead>
		<tr>
		  <th>订单号</th>
		  <th>名称</th>
		  <th>用户</th>
		  <th>付款金额</th>
		
		  <th>订单日期</th>
		</tr>
	  </thead>
	  <tbody>
	  <?php 
		foreach($list as $k=>$v){
	  ?>
		<tr>
		<td><?php echo $v->sn;?></td>
		<td><?php echo get_vip_name($v->type-1);?></td>
		<td><?php echo $v->user;?></td>
		<th><?php echo toprice($v->price);?></th>
		
		<td><?php echo date("Y-m-d H:i",$v->time);?></td>
		</tr>
		<?php 
		}
		?>
	  </tbody>
  </table>
	<?php echo $paginate->links();?>
		<?php }else{?>
		暂无数据
		<?php }?>
    </div>
</div>