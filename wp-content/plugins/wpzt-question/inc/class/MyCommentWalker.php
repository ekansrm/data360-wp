<?php
class MyCommentWalker extends \Walker_Comment {
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1;
        if($depth>0) return $output;

        switch ( $args['style'] ) {
            case 'div':
                break;
            case 'ol':
                $output .= '<ol class="comment-children" style="display:none;">' . "\n";
                break;
            case 'ul':
            default:
                $output .= '<ul class="comment-children" style="display:none;">' . "\n";
                break;
        }
    }
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1;
        if($depth>0) return $output;

        switch ( $args['style'] ) {
            case 'div':
                break;
            case 'ol':
                $output .= "</ol>\n";
                break;
            case 'ul':
            default:
                $output .= "</ul>\n";
                break;
        }
    }

    public function html5_comment( $comment, $depth, $args ) {
        $GLOBALS['comment'] = $comment;
		
        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        $author = get_comment_author();
		if(mb_strlen($author)>=8){
			$author=mb_substr($author,0,3).'***'.mb_substr($author,-4);
		}
        $reply = '';
        if($depth>0 && $comment->comment_parent){
            $reply = get_comment_author($comment->comment_parent);
            $reply = $reply ? ' 回复 <a href="#comment-' . $comment->comment_parent.'">'.$reply.'</a>' : '';
        }
       
			if(!empty($comment->user_id)){
				$url=home_url('userpost/'.$comment->user_id);
				$author = '<a href="'.$url.'" target="_blank">'.$author.'</a>';
			}
		?>
        <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
        <div id="div-comment-<?php comment_ID() ?>" class="comment-inner clearfix">
            <div class="comment-author vcard ">
                <?php if ( $args['avatar_size'] != 0 ){
					$avatarurl=get_user_avatar($comment->user_id);
					echo "<img src='{$avatarurl}' width='{$args['avatar_size']}'/>";
				}		
				?>
            </div>
            <div class="comment-body">
                <div class="nickname"><?php echo $author.$reply;?>
                    <span class="comment-time" time="<?php echo get_comment_date('Y-m-d H:i:s');?>"><?php echo pq_timeago(get_comment_date('Y-m-d H:i:s')); ?></span>
					<span class="reply pull-right">
					 <?php echo ''; //comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						<a class="comment-reply-button" data-postid='<?php echo get_the_ID();?>' data-commentid='<?php comment_ID();?>'>回复</a>
					</span>
                </div>
                <?php if ( $comment->comment_approved == '0' ) {?>
                    <div class="comment-awaiting-moderation">等待审核中……</div>
						<?php if($uid==$comment->user_id){  //显示自己的评论?>
							<div class="comment-text"><?php comment_text(); ?></div>
						<?php }?>
                <?php }else{ ?>
                <div class="comment-text">
				<?php comment_text(); ?>
				<?php
				if($comment->comment_parent==0){	//顶级显示评论数
					$childcount=$this->get_children_count($comment,0);
					?>
					<span class="wpzt-childcount" data-count='<?php echo $childcount;?>' data-show="0"><?php echo $childcount?>条回复</span>
					<?php }?>
				</div>
				<?php }?>
			
			
			</div>

        </div>
        <?php
    }
	
	public function get_children_count($comment,$count){
		$child=$comment->get_children(['status'=>'approve']);
		$child_count=count($child);
		if($child_count==0){
			return $count;
		}else{
			$count+=$child_count;
			foreach($child as $v){
				$nextcount=$this->get_children_count($v,0);
				$count+=$nextcount;
			}
			return $count;
		}
	}
}