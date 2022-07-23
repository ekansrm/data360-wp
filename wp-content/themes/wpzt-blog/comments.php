<?php
    if (post_password_required()) {
			return;	
		}
		if(get_option('default_comment_status')!="open"){
			return;
		}
	add_js('wangeditor');
	add_js('jquery.validate');
	add_js('comment');
	global $current_user;

		add_filter('comment_form_default_fields', 'wpzt_comment_remove_fields'); 
		function wpzt_comment_remove_fields($fields) {
			if(isset($fields['url'])){
				unset($fields['url']);
			}
			if(isset($fields['cookies'])){
				unset($fields['cookies']);
			}
			return $fields;
		}
		$avatar=get_user_avatar($current_user->ID);
		if(!get_option( 'comment_registration' )){
			update_option('comment_registration',1);
		}
		remove_action('init', 'kses_init');//匿名评论时内容可以有html
		remove_action('set_current_user', 'kses_init');//匿名评论内容可以有html
?>
 

<!--评论-->
<script>
 var facial="<?php echo WPZT_IMG.'/facial/'?>";
</script>
<div id="comments" class="entry-comments">
    <?php
        global $post_id;
		comment_form([
			'title_reply_before' => '<div class="comment-title"><h3><i class="mian-bdcolor"></i>',
			'title_reply_after'  => '</h3></div>',
			'comment_field' =>  '<div class="comment-form-comment"><div id="editor"></div><textarea id="comment" name="comment" class="required" style="display:none;" rows="3"></textarea></div>',      
			'label_submit' => '发表评论',
			'class_submit'=>'submit mian-bgcolor',
			'format' => 'html5',
			'must_log_in'=>sprintf("<div class='must_log_in'><div class='must_login'>请先<a href='%s' class='mian-color'>登录</a>账户再评论哦</div></div>",wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ), $post_id ) )),
			
			'logged_in_as'=>sprintf("<div class='logged_in_as'><img src='%s'/></div>",$avatar)
		]);
    ?>
	<?php if ( have_comments()){ ?>
	<div class="havecomment-list">
		<div  class="comment-title">
		<h3>
		<i class="mian-bdcolor"></i>
			<?php
			$comments_number = get_comments_number();
			printf('用户评论(%s)', number_format_i18n( $comments_number ));
			?>
		</h3>
		</div>
		<ul class="comments-list">
			<?php
            wp_list_comments([
                'walker' => new \wpzt\common\MyCommentWalker(),
                'style'       => 'ul',
                'short_ping'  => true,
                'type'        => 'comment',
                'avatar_size' => '60',
                'format'    => 'html5'
            ]);
			?>
		</ul><!-- .comment-list -->
        <div class="comment-pagination clearfix">
            <?php paginate_comments_links(array('prev_text'=>'前一页','next_text'=>'下一页'));?>
        </div>
        </div>
	<?php } // Check for have_comments(). ?>
</div><!-- .comments-area -->
	