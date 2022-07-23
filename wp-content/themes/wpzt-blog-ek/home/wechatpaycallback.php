<?php
	ob_clean();
	$wxpay_appid=wpzt('wechatappid');
	$wxpay_mchid=wpzt('wechatmchid');
	$wxpay_key=wpzt('wechatkey');
	$params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
	$params->appID = $wxpay_appid;
    $params->mch_id = $wxpay_mchid;;
    $params->key =$wxpay_key;
	$pay = new \Yurun\PaySDK\Weixin\SDK($params);
	
	class PayNotify extends \Yurun\PaySDK\Weixin\Notify\Pay{
		/**
		 * 后续执行操作
		 * @return void
		 */
		protected function __exec(){
			// 支付成功处理，一般做订单处理，$this->data 是从微信发送来的数据
			
			//商户本地订单号
			$ordersn = $this->data['out_trade_no'];
			//微信交易号
			$paysn = $this->data['transaction_id'];

			// 处理本地业务逻辑
			
			$common=new \wpzt\pay\Common();
			$common->payed_order($ordersn,$paysn,2);
			// 告诉微信我处理过了，不要再通过了
			$this->reply(true, 'OK');
		}
	}


		$payNotify = new PayNotify;
		try {
			$pay->notify($payNotify);
		} catch (Exception $e) {
			error_log( $e->getMessage() . ':' . var_export($payNotify->data, true));
		}