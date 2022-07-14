<?php wp_enqueue_script('jquery2.1',WPZT_JS.'/jquery2.1.1.min.js');
 wp_enqueue_script('letan',WPZT_JS.'/letan.js'); ?>
<p class="submit">
	<button type="button" onclick="clearcache()" class="button button-primary">刷新缓存</button>
</p>
<p class="description">
	注意：发布完文章后记得来刷新一下缓存
</p>
<div id="cachenum"></div>
<div id="frameset" style="display:none;">

</div>
<script>
	jQuery(function($){
	 clearcache=function(){	//清除缓存
		var url="<?php echo admin_url('admin-ajax.php');?>";
		var option={};
		option.success=function(res){
			refresh(res.url);
			letan.tip(res.msg);
		}
		var param={'action':'clearcache'};
		letan.call(url,param,option);
	}
	function refresh(url){
		for(var v in url){
			$("#frameset").load(url[v]);
			$("#cachenum").text('刷新页面中……');
		}
		$("#cachenum").text('页面刷新完成');
	}
	
})

	
</script>