<?php
namespace wpzt\pay;


class Alipay{
	private $params;
	private $pay;
	//private $common;
	function __construct(){
		$this->params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
		$this->params->appID=wpzt('aliappid');
		$this->params->appPrivateKey=wpzt('alipay_pri');
		$this->params->appPublicKey=wpzt('alipay_pub');
		$this->pay = new \Yurun\PaySDK\AlipayApp\SDK($this->params);
		//$this->common=new \wpzt\pay\Common();
	}
	
	function mypay($sn,$price,$subject,$return_url){
		if(wp_is_mobile()){
			$url=$this->mobile($sn,$price,$subject,$return_url);
			return ['type'=>'link','url'=>$url,'paytype'=>2];
		}else{
			$url=$this->pagepc($sn,$price,$subject,$return_url);
			return ['type'=>'link','url'=>$url,'paytype'=>2];
		}
	}
	
	function pc($sn,$price,$subject){	//二维码
		$request = new \Yurun\PaySDK\AlipayApp\FTF\Params\QR\Request;
		$request->notify_url = home_url('alipaycallback'); // 支付后通知地址（作为支付成功回调，这个可靠）
		//$request->notify_url = "http://www.wpmee.com/zzu";
		$request->businessParams->out_trade_no = $sn; // 商户订单号
		$request->businessParams->total_amount =$price; // 价格
		$request->businessParams->subject = $subject; // 商品标题
		try{
			$data = $this->pay->execute($request);
			//var_dump('result:', $data);			
			//var_dump('success:', $this->pay->checkResult());			
			//var_dump('error:', $this->pay->getError(), 'error_code:', $pay->getErrorCode());
			/*<img src="http://qr.liantu.com/api.php?text=<?php echo urlencode($data['alipay_trade_precreate_response']['qr_code']);?>"/>*/
			return $data;
		}
		catch(Exception $e){
			var_dump($this->pay->response->body());
		}
	}
	
	function mobile($sn,$price,$subject,$return_url){
		$request = new \Yurun\PaySDK\AlipayApp\Wap\Params\Pay\Request;
		$request->notify_url = home_url('alipaycallback');  // 支付后通知地址（作为支付成功回调，这个可靠）
		$request->return_url = $return_url; // 支付后跳转返回地址
		$request->businessParams->out_trade_no = $sn; // 商户订单号
		$request->businessParams->total_amount = $price; // 价格
		$request->businessParams->subject =$subject; // 商品标题

		// 跳转到支付宝页面
		//$pay->redirectExecute($request);
		$this->pay->prepareExecute($request, $url);
		return $url;
	}

	function pagepc($sn,$price,$subject,$return_url){//pc网页跳转
		$request = new \Yurun\PaySDK\AlipayApp\Page\Params\Pay\Request;
		$request->notify_url = home_url('alipaycallback'); // 支付后通知地址（作为支付成功回调，这个可靠）
		$request->return_url = $return_url; // 支付后跳转返回地址
		$request->businessParams->out_trade_no = $sn; // 商户订单号
		$request->businessParams->total_amount = $price; // 价格
		$request->businessParams->subject = $subject; // 商品标题
		$this->pay->prepareExecute($request, $url);
		return $url;
	}


}