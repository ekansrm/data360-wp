<?php
	$viptype=wpzt\form\Request::get('viptype');
	$pid=wpzt\form\Request::get('pid');
	$uid=get_current_user_id();
	$userviptype=get_vip_type($uid);
	if(!empty($pid)){	//购买视频
		if(!wpzt\form\Validate::isInt($pid)){
			wp_redirect('404');
		}
		$nologin=wpzt('nologinpay');
		
		if(!$nologin&&empty($uid)){
				wp_redirect(get_login_url());
		}
		if(empty($uid)){
			set_pay_cookie();//免登录cookie
		}
		$post=get_post($pid);
		
		if(empty($post)){
			wp_redirect('404');
		}
		$post_url=get_permalink($pid);
		$pname=$post->post_title;
		$meta=wpzt_post($pid);
		
		switch($meta['viewset']){
			case 1:wp_redirect('404');
			case 2:				//会员免费
				if($userviptype>0){wp_redirect($post_url);}
				$pprice=$meta['price'];
				$poldprice=$meta['price'];break;
			case 3:
				if($userviptype>0){
					$pprice=get_vipprice($meta);
					$poldprice=$meta['price'];
				}else{
					$pprice=$meta['price'];
					$poldprice=$meta['price'];
				}
				break;
			case 4:wp_redirect($post_url);break;
			default:
				wp_redirect($post_url);
		}
		$videourl=urlencode(get_permalink($pid));
	}
	
	if(!empty($viptype)){	//购买VIP
		if(!\wpzt\form\Validate::isInt($viptype)){
			wp_redirect('404');
		}
		if($viptype>4){
			wp_redirect('404');
		}
		if(empty($uid)){
			wp_redirect(get_login_url());
		}
		if($userviptype==4){
			wp_redirect(add_query_arg(['error'=>'已经是终身会员'],home_url('usercenter')));
		}
		$total=wpzt('vip'.$viptype);
		if(empty($total)||!is_numeric($total)){
			wp_redirect('404');//不存在的VIP
		}
		$pname=get_vip_name($viptype);
		$poldprice=$total;
		$pprice=$total;
	}
		
	
	if(wpzt('wechattype')!=0){	//支付图标的显示管理
		$paytype=1;
	}elseif(wpzt('alitype')!=0){
		$paytype=2;
	}else{
		$paytype=0;
	}
	
	
	
	get_header();
	add_js('jquery.qrcode.min');
	add_js('cash');
	
	
?>
<div class="pagebody pt20 pb30 usarticles">
	
	<input type="hidden" id="viptype" value="<?php echo empty($viptype)?0:$viptype ?>"/>
	<input type="hidden" id="pid" value="<?php echo empty($pid)?0:$pid;?>"/>
	<input type="hidden" id="paytype" value="<?php echo $paytype;?>"/>
	<input type="hidden" id="ordersn" value=""/>
	<input type="hidden" id="orderlisturl" value="<?php echo home_url('orderlist');?>"/>
	<input type="hidden" id="videourl" value="<?php echo empty($videourl)?'':$videourl;?>"/>
	
	<!--列表-->
	<div class="container">
	<!--内容-->
	<div class="merchants container">
	<h2 class="mian-bgcolor">购买详情</h2>
	<div class="paytable plr30 ptb60">
	<div class="pay-table">
	<table class="col100">
  <thead class="paytablebg ">
    <tr>   
      <th scope="col" style="width:40%">名称</th>
      <th scope="col">价格（元）</th>
      <th scope="col">优惠（元）</th>
      <th scope="col">实付（元）</th>
    </tr>
  </thead>
  <tbody>
    <tr>     
      <td><?php echo $pname;?></td>
      <td><?php echo $poldprice;?></td>
      <td><?php echo $poldprice-$pprice;?></td>
      <td class="paycolor"><?php echo $pprice;?></td>
    </tr>
  </tbody>
</table>
</div>
	<div class="pay-all paytablebg">
	<span><strong>应付总价</strong></span>
	<span><small>￥</small><?php echo $pprice;?></span>
	</div>
	<div class="pay-way  d-flex fs-c">
	<span class="mr20"><strong>支付方式</strong></span>
	<div class="choose-payway  d-flex jc-c ai-c">
	<?php if(wpzt('wechattype')!=0){?>
	<a onclick="checktype(this)" data-type="1" class="chpaybtn <?php if($paytype==1){?>active<?php }?>"><i class="iconfont icon-weixin"></i>微信支付</a>
	<?php }?>
	<?php if(wpzt('alitype')!=0){?>
	<a onclick="checktype(this)" data-type="2" class="chpaybtn <?php if($paytype==2){?>active<?php }?>"><i class="iconfont icon-zhifubao1"></i>支付宝支付</a>
	<?php }?>
	<?php if($paytype==0){?><span>支付方式待添加</span><?php }?>
	</div>
	</div>
	
	</div>
	<div class="merchbtn">
	
	<button type="button" onclick="payorder()" class="payallsubmit mian-bgcolor">提交订单</button>
	
	</div>
	</div>
	<div class="container ptb30 hint">
	<h4><i class="iconfont icon-lujing330"></i>温馨提示：</h4>
	<p>* 本站支持支付宝和微信支付，如需使用其他支付方式请联系客服。</p>
	<p>*请认准本站网址，其他渠道有可能出现挂马情况，造成损失本站不予负责。</p>
	<p>* 购买之前请认真了解所购买的产品，视频类产品由于其特殊性一旦购买无法退换。</p>
	<p>支付后如无跳转请刷新视频，VIP请至用户中心查看</p>
	
	</div>
	</div>
	</div>
	
	<!----微信弹出框----->
	<div class="modal fade qrmodal" id="payqrmodal">
	  <div class="modal-dialog">
		<div class="modal-content">
	 
		  <!-- 模态框头部 -->
		  <p>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		  </p>
	 
		  <!-- 模态框主体 -->
		  
		  <input type="hidden" id="single_permalink" value="<?php the_permalink();?>"/>
		  <div  class="modal-body">
				<div class="qrcode" id="pay-qrcode"></div>
		  </div>
			<div class="modal-weixin"><i class="iconfont icon-weixin"></i>微信扫码支付</div>
			<p class="info">支付后如无跳转请刷新页面查看<br/>
			购买VIP的用户请至用户中心查看
			</p>
		
	 
		</div>
	  </div>
	</div><!----弹出框结束------>
	
<?php
	get_footer();