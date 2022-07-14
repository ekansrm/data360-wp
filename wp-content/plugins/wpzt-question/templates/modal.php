<?php
	$all_terms=wpzt_pq('category');
	
?>
<div class='modask'>
		<input id ="loginurl" type="hidden" value="<?php echo get_pq_login_url();?>"/>
		<div class='modask-ctn container'>
			<a href='javascript:;' title='关闭' class='modaskclose'><i class='iconfont icon-error'></i></a>
			<form id="add_question">
				<?php wp_nonce_field('add_question_action','add_question_field');?>
				<input type="hidden" name="action" value="add_question"/>
				<div class='modask-item modask2-item'>
					<div class='modask-item-1'>
						<label>标题：</label>
						<input type='text' name="title" id="title" value=""  class="form-control"/>
					</div>
					<div class='modask-item-2'>
						<label>分类：</label>
						<select name="category" class="form-control">
						<?php 
							if(!empty($all_terms)){
								foreach($all_terms as $v){
									$term=get_term($v,'question');
						?>
						  <option value="<?php echo $v;?>"><?php echo $term->name;?></option>
						<?php
							}}
						?>
						</select>
					</div>
				</div>
				<textarea name="content" style="display:none;" id="question_content"></textarea>
				<div class='modask-edit'>
				<!-- 编辑器 -->
				</div>
				<button type='button' onclick="add_question()" class='btn btn-primary modask-btn'>发布</button>
			</form>
		</div>
	</div>