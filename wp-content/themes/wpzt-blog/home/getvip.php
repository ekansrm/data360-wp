<?php
add_title('开通会员');
get_header();
$cashurl=home_url('cash');
$home_cover=wpzt_img('home-cover');
$home_cover=empty($home_cover)?WPZT_IMG.'/userbannerbg.jpg':$home_cover;
?>


	
	<div class="vipctn container mt50">
	<div class=" vipctn-title">	
			<h2>成为会员</h2>
			<p>成为我们的会员，可以享受VIP专属福利，高级会员可享低级会员所有特权哦！</p>
		</div>
	<div class=" d-flex sb-s fw-w">
		<!--~~~-->
	<div class="vipctn-item mt40 vipctn-item1">
	<div class="vipctn-item-top vipctn-item-top1 text-white">
	<h3>月度会员</h3>
	<p>现在开通即可享受会员权益</p>
	
	</div>
	<div class="vipctn-item2-top">
	<span>￥<span><?php echo wpzt('vip1');?></span>/月</span>
	<a title="月度会员" href="<?php echo add_query_arg(['viptype'=>1],$cashurl);?>" >立即开通</a>
	</div>
		<ul>
		  <?php echo wpzt( 'vipright');?>
			<!-- <li>
			<span>每天免费资源下载</span>
			<span><?php echo wpzt('numvip1');?>个</span></li>-->
			<li>
			  <span>免费专享</span>
			  <span>月度VIP专享</span></li>
			<li>
			  <span>客服咨询</span>
			  <span>优先处理</span></li>
			<li>
			  <span>会员有效期</span>
			  <span>一个月</span></li>
		</ul>
	</div>
			<!--~~~-->
	<div class="vipctn-item mt40 vipctn-item2">
	<div class="vipctn-item-top vipctn-item-top2">
	<h3>季度会员</h3>
	<p>现在开通即可享受会员权益</p>
	</div>
	<div class="vipctn-item2-top">
	<span>￥<span><?php echo wpzt('vip2');?></span>/季度</span>
	<a title="季度会员" href="<?php echo add_query_arg(['viptype'=>2],$cashurl);?>">立即开通</a>
	</div>
		<ul>
		  <?php echo wpzt( 'vipright');?>
			
			<li>
			  <span>免费专享</span>
			  <span>季度VIP专享</span></li>
			<li>
			  <span>客服咨询</span>
			  <span>优先处理</span></li>
			<li>
			  <span>会员有效期</span>
			  <span>三个月</span></li>
		</ul>
	</div>
			<!--~~~-->
	<div class="vipctn-item mt40  vipctn-item3">
	<div class="vipctn-item-top vipctn-item-top3">
	<h3>年度会员</h3>
	<p>现在开通即可享受会员权益</p>
	</div>
	<div class="vipctn-item2-top">
	<span>￥<span><?php echo wpzt('vip3');?></span>/年</span>
	<a title="年度会员" href="<?php echo add_query_arg(['viptype'=>3],$cashurl);?>">立即开通</a>
	</div>
			<ul>
		  <?php echo wpzt( 'vipright');?>
			
			<li>
			  <span>免费专享</span>
			  <span>年度VIP专享</span></li>
			<li>
			  <span>客服咨询</span>
			  <span>优先处理</span></li>
			<li>
			  <span>会员有效期</span>
			  <span>一年</span></li>
		</ul>
	</div>
				<!--~~~-->
	<div class="vipctn-item mt40  vipctn-item4">
	<div class="vipctn-item-top vipctn-item-top4">
	<h3>终身会员</h3>
	<p>已开通会员，补差价即可成为终生会员</p>
	</div>
	<div class="vipctn-item2-top">
	<span>￥<span><?php echo wpzt('vip4');?></span>/年</span>
	<a title="终身会员" href="<?php echo add_query_arg(['viptype'=>4],$cashurl);?>">立即开通</a>
	</div>
			<ul>
		  <?php echo wpzt( 'vipright');?>
			
			<li>
			  <span>免费专享</span>
			  <span>终身VIP专享</span></li>
			<li>
			  <span>客服咨询</span>
			  <span>优先处理</span></li>
			<li>
			  <span>会员有效期</span>
			  <span>永久</span></li>
		</ul>
	</div>
	</div>

<!-- Modal -->
<input type="hidden" id="paytype" value="1"/>
<!----
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
选择支付方式      
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <div class="choose-payway  d-flex jc-c ai-c mt40">
	<?php if(wpzt('wxpay')){?>
	<a class="chpaybtn active" data-type="1"><i class="iconfont icon-weixin"></i>微信支付</a>
	<?php }?>
	<?php if(wpzt('alipay')){?>
	<a class="chpaybtn " data-type="2"><i class="iconfont icon-zhifubao"></i>支付宝支付</a>
	<?php }?>
	</div>
	<div class="merchbtn mt40" style="margin-bottom:40px;">
				<button type="button" class="payallsubmit" onclick="submit_order()">提交订单</button>
				</div>
      </div>
    </div>
  </div>
</div>
--->

	</div>
<!-- 常见问题 -->
<!-- 	<div class="vipfaq container">
	<div class="vipfaq-title">
	<h3>常见问题</h3>
	<p>FAQ</p>
	</div>
	<div class="vipfaq-item">
	<h5>如何成为会员？</h5>
	<p></p>
	</div>
	</div> -->


<?php
get_footer();