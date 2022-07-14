<?php
namespace wpzt\pay;

class Pay{
	private $payobject;
	function __construct($paytype){ //1微信2支付宝
		if($paytype==1){//微信
			switch(wpzt('wechattype')){
				case 1:$this->payobject=new \wpzt\pay\Wechatpay();break;//官方
				case 2:$this->payobject=new \wpzt\pay\Xunhupay(4);break;//迅虎
			}
		}elseif($paytype==2){//支付宝
			switch(wpzt('alitype')){
				case 1:$this->payobject=new \wpzt\pay\Alipay();break;//官方
				case 2:$this->payobject=new \wpzt\pay\Xunhupay(3);break;//迅虎
			}
		}
	}
	
	function userpay($sn,$price,$subject,$return_url=''){
		if(empty($return_url)){
			$return_url=home_url('orderlist');
		}
		return $this->payobject->mypay($sn,$price,$subject,$return_url);
	}
}