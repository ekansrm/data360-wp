<?php
	use \wpzt\form\Request as Req;
	 require ABSPATH . WPINC . '/class-wp-editor.php';
	$myscreen='sendpost';
	require_once locate_template('template-parts/usercenter/header.php');
	$postid=intval(Req::get('pid'));
	$catlist=wpzt('user_post_cat');
	$uid=get_current_user_id();
	$user=get_user_by('ID',$uid);
	if(empty($user)){
		wp_redirect(get_login_url());
	}else{
		if(!user_can_sendpost($user)){
			wp_redirect(add_query_arg(['errormsg'=>'暂无权限发布文章，请联系管理员'],home_url('usercenter')));
		}
	}

	if(empty($postid)){//添加
		$postid=0;
		$post_title='';
		$post_content='';
		$post_category=[0];
		$post_status='draft';
	}else{//编辑
		$post=get_post($postid);
		if($post->post_author!=$uid){
			wp_redirect(add_query_arg(['errormsg'=>'只能编辑自己的文章'],home_url('usercenter')));
		}
		$post_title=$post->post_title;
		$post_content=$post->post_content;
		$post_status=$post->post_status;
		$category=get_the_category($postid);
		if(empty($category)){
			$post_category=[0];
		}else{
			$post_category=wp_list_pluck( $category, 'term_id' );
		}
	}
?>
	<div class='ucctn-ctn uc-sendpost'>
		<form id="post">
			<?php wp_nonce_field('save_post_action','save_post_nonce_field'); ?>
				<input type="hidden" name="action" value="save_post"/>
				<input type="hidden" name='postid' value="<?php echo $postid;?>"/>
				<div class="form-group">
					<label class='sendpost-title'>标题</label>
					<input type="text" id="title" name="title" value="<?php echo $post_title?>" class="form-control" placeholder="标题"/>
				</div>
				<div class="form-group">
					<label class='sendpost-title'>内容</label>
					<?php wp_editor(  wpautop($post_content), 'post_content', array('media_buttons'=>true, 'quicktags'=>true)); ?>
				</div>
					<div class="form-group">
						<label>选择分类</label>
						<?php
							if(!empty($catlist)){
								foreach($catlist as $v){
						?>
						<div class="form-check form-check-inline">
							 <label class="form-check-label">
								<input type="checkbox" <?php if(in_array($v,$post_category)){?>checked<?php }?> class="form-check-input" name="category[]" value="<?php echo $v?>"/><?php echo get_cat_name($v);?>
							  </label>
						</div>
						<?php
								}
							}else{
						?>
							<label>没有可发布文章的分类(后台主题设置可发布的类型)</label>
							<?php }?>
					</div>
					<div class="form-group">
						<label class="form-check-label">
						<input type="radio" <?php if($post_status=='draft'){?>checked<?php }?> name="status" value="draft">草稿</label>
						<label class="form-check-label">
						<input type="radio" <?php if($post_status=='pending'){?>checked<?php }?> name="status" value="pending">审核</label>
						<?php
							if(wpzt('user_post_status')){	//是否开启直接发布
						?>
						<label class="form-check-label">
						<input type="radio" <?php if($post_status=='publish'){?>checked<?php }?> name="status" value="publish">发布</label>
						<?php
						}
						?>
					</div>
		
		</form>
			<button type="button" onclick="save_post(this)" class="btn btn-primary sendpost-btn">提交</button>
	</div>

	</div>
	</div>
<?php
	get_footer();