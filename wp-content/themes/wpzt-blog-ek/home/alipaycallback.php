<?php
	ob_clean();
	$params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
	$params->appID=wpzt('aliappid');
	$params->appPublicKey = wpzt('alipay_pub');
	$params->appPrivateKey = wpzt('alipay_pri');
	$pay = new \Yurun\PaySDK\AlipayApp\SDK($params);
	if ($pay->verifyCallback($_POST)) {
		// 模式2通知验证成功，可以通过POST参数来获取支付宝回传的参数
		$pay_verify = true;
	}else{
		$pay_verify = false;//wplog($_POST);
		exit;
		//$pay_verify = true;
	}
	$ordersn = $_POST['out_trade_no'];//商户本地订单号
	$paysn = $_POST['trade_no'];//支付宝交易号
	if ($pay_verify && $_POST['trade_status'] == 'TRADE_SUCCESS') {
		//处理业务逻辑
			$common=new \wpzt\pay\Common();
			$common->payed_order($ordersn,$paysn,1);
		echo 'success';exit;
	}else{
		
		echo 'error';
	}
	
	