<link href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo WPZT_JS?>/letan.js"></script>
<script src="<?php echo WPZT_JS?>/opadmin.js"></script>
<script>
	ajaxurl="<?php echo admin_url('admin-ajax.php');?>";
</script>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">
				用户操作
			</h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<label>用户名</label>
				<input type="text" id="vipusername" value=""/>
				<select id="viptype">
					<option value="1">月度会员</option>
					<option value="2">季度会员</option>
					<option value="3">年度会员</option>
					<option value="4">终身会员</option>
				</select>
				<button onclick="add_vip()" class="btn btn-primary">添加</button>
			</div>
		
		</div>
	</div>
</div>