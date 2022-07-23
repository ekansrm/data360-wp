<?php
namespace wpzt\pay;


class Wechatpay{
	private $params;
	private $pay;
	function __construct(){
		$this->params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
		$this->params->appID = wpzt('wechatappid');
		$this->params->mch_id = wpzt('wechatmchid');
		$this->params->key = wpzt('wechatkey');
		$this->pay = new \Yurun\PaySDK\Weixin\SDK($this->params);
		
	}
	
	function mypay($sn,$price,$subject,$return_url){//$return_url参数没什么用
		if(wp_is_mobile()){
			$url=$this->mobile($sn,$price,$subject);
			return ['type'=>'link','url'=>$url,'paytype'=>1];
		}else{
			$url=$this->pc($sn,$price,$subject);
			return ['type'=>'qr','url'=>$url,'paytype'=>1];
		}
	}
	
	function pc($sn,$price,$subject){
		$request = new \Yurun\PaySDK\Weixin\Native\Params\Pay\Request;
		$request->body = $subject; // 商品描述
		$request->out_trade_no = $sn; // 订单号
		$request->total_fee = $price*100; // 订单总金额，单位为：分
		$request->spbill_create_ip = $this->getIp(); // 客户端ip
		$request->notify_url = home_url('wechatpaycallback'); // 异步通知地址
		$result = $this->pay->execute($request);
		if(empty($result['code_url'])){
			return false;
		}
		$shortUrl = $result['code_url'];
		//var_dump($result, $shortUrl);
		return $shortUrl;
	}
	
	function mobile($sn,$price,$subject){
		$request = new \Yurun\PaySDK\Weixin\H5\Params\Pay\Request;
		$request->body = $subject; // 商品描述
		$request->out_trade_no = $sn; // 订单号
		$request->total_fee = $price*100; // 订单总金额，单位为：分
		$request->spbill_create_ip = $this->getIp(); // 客户端ip，必须传正确的用户ip，否则会报错
		$request->notify_url = home_url('wechatpaycallback'); // 异步通知地址
		$request->scene_info = new \Yurun\PaySDK\Weixin\H5\Params\SceneInfo;
		$request->scene_info->type = 'Wap'; // 可选值：IOS、Android、Wap
		// 下面参数根据type不同而不同
		$request->scene_info->wap_url = home_url();
		$request->scene_info->wap_name = get_bloginfo('name');
		$result = $this->pay->execute($request);
		if($this->pay->checkResult()){
				// 跳转支付界面
				//header('Location: ' . $result['mweb_url']);
				return $result['mweb_url'];
			}else{
				//var_dump($pay->getErrorCode() . ':' . $pay->getError());
				return false;
			}
			//exit;
			return false;
	}
	
	private function getIp(){
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
				$cip = $_SERVER["HTTP_CLIENT_IP"];
			}else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}else if(!empty($_SERVER["REMOTE_ADDR"])){
				$cip = $_SERVER["REMOTE_ADDR"];
			}else{
				$cip = '';
			}
			preg_match("/[\d\.]{7,15}/", $cip, $cips);
			$cip = isset($cips[0]) ? $cips[0] : '127.0.0.1';
			unset($cips);
			return $cip;
	}
	
}