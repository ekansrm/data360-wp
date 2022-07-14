<?php
	$bday=mktime(0,0,0,date('m'),date('d'),date('Y'));
	$bmonth=mktime(0,0,0,date('m'),1,date('Y'));
	$lmonth=mktime(0,0,0,date('m')-1,1,date('Y'));
	/*********订单数*************/
	global $wpdb;
	$dayordernum=$wpdb->get_var("select count(*) from {$wpdb->prefix}wpzt_order where time>{$bday} and status=1 and type=1");
	$monthordernum=$wpdb->get_var("select count(*) from {$wpdb->prefix}wpzt_order where time>{$bmonth} and status=1 and type=1");
	$lmonthordernum=$wpdb->get_var("select count(*) from {$wpdb->prefix}wpzt_order where time>{$lmonth} and time<{$bmonth} and status=1 and type=1");
	$dayvipnum=$wpdb->get_var("select count(*) from {$wpdb->prefix}wpzt_order where time>{$bday} and status=1 and type>1 and type<=5");
	$monthvipnum=$wpdb->get_var("select count(*) from {$wpdb->prefix}wpzt_order where time>{$bmonth} and status=1 and type>1 and type<=5");
	$lmonthvipnum=$wpdb->get_var("select count(*) from {$wpdb->prefix}wpzt_order where time>{$lmonth} and time<{$bmonth} and status=1 and type>1 and type<=5");
	/**********订单金额************/
	$daymoney=$wpdb->get_var("select sum(price) from {$wpdb->prefix}wpzt_order where time>{$bday} and status=1");
	$monthmoney=$wpdb->get_var("select sum(price) from {$wpdb->prefix}wpzt_order where time>{$bmonth} and status=1");
	$lmonthmoney=$wpdb->get_var("select sum(price) from {$wpdb->prefix}wpzt_order where time>{$lmonth} and time<{$bmonth} and status=1");
	
	get_template_part('template-parts/admin/admin-static');
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            商城总览
        </h3>
    </div>
    <div class="panel-body">
	<table class="table">
		<tr>
			<td>今日资源订单</td><td><?php echo $dayordernum;?>个</td>
			<td>本月资源订单</td><td><?php echo $monthordernum; ?>个</td>
			<td>上月资源订单</td><td><?php echo $lmonthordernum?>个</td>
		</tr>
		<tr>
			<td>今日VIP订单</td><td><?php echo $dayvipnum;?>个</td>
			<td>本月VIP订单</td><td><?php echo $monthvipnum;?>个</td>
			<td>上月VIP订单</td><td><?php echo $lmonthvipnum;?>个</td>
		</tr>
		<tr>
			<td>今日订单金额</td><td><?php echo toprice($daymoney);?>元</td>
			<td>本月订单金额</td><td><?php echo toprice($monthmoney); ?>元</td>
			<td>上月订单金额</td><td><?php echo toprice($lmonthmoney);?>元</td>
		</tr>
		
	<!----	<tr>
			<td>今日新增注册</td><td></td>
			<td>本月新增注册</td><td></td>
			<td>上月新增注册</td><td></td>
		</tr>--->
	</table>	
	</div>
</div>