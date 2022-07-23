<?php
	use \wpzt\form\Request as Req;
	$myscreen='mypostlist';
	require_once locate_template('template-parts/usercenter/header.php');
	$paged=get_query_var('paged');
	$uid=get_current_user_id();
	$paged=empty($paged)?1:$paged;
	$args=['author'=>$uid,'paged'=>$paged,'post_status'=>['publish','pending','draft']];
	$the_query=new WP_Query($args);
	?>
	

<div class='ucctn-ctn uc-postlist'>
	<table>
  <thead>
    <tr>
      <th scope="col">文章名称</th>
      <th scope="col">日期</th>
      <th scope="col">状态</th>
      <th scope="col">编辑</th>
      <th scope="col">删除</th>
    </tr>
  </thead>
  <tbody>
  <?php 
	if($the_query->have_posts()){
		while($the_query->have_posts()){
			$the_query->the_post();
			$pid=get_the_ID();
  ?>
    <tr>
      <td class='post-firsttd'><a href="<?php the_permalink();?>" class='posttitle'><?php the_title();?> </a></td>
		<td class='textalign-l'><?php the_time('Y-m-d');?></td>
      <td><?php echo get_post_status_name(get_post_status($pid));;?></td>
      <td><a href='<?php echo get_edit_post($pid);?>' class='postedit'><i class='iconfont icon-edit-write'></i>编辑</a></td>
      <td><button onclick="delete_post(<?php echo $pid;?>)"><i class='iconfont icon-bin'></i>删除</button></td>
     
    </tr>
   <?php
		}
		wp_reset_postdata();
	}
	?>
  
  </tbody>
</table>
	</div>
	<!-- 分页 --> 
	
	<div class='w-fy'>
		<div class='w-fylink'>
			<?php  letan_page($the_query);;?>
		</div>
	</div>
	
	</div>
	</div>
	<?php
	
	get_footer();
	