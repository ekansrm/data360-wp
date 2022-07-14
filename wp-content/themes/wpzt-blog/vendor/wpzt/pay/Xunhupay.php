<?php
namespace wpzt\pay;
use wpzt\pay\Xunhuapi as xhapi;
use wpzt\form\Request as Req;


class Xunhupay{
	private $appid;
	private $appsecret;
	private $my_plugin_id;
	private $paytype;
	function __construct($paytype=3){//3支付宝4微信
		if($paytype==3){
			$this->paytype=2;//返回支付宝为2
			$this->appid=wpzt('xhalipay_appid');
			$this->appsecret=wpzt('xhalipay_key');
			$this->my_plugin_id='wpzt_hot_alipay';
		}elseif($paytype==4){
			$this->paytype=1;	//返回微信为1
			$this->appid=wpzt('xhwechat_appid');
			$this->appsecret=wpzt('xhwechat_key');
			$this->my_plugin_id='wpzt_hot_wechat';
		}
		
	}
	
	function mypay($sn,$price,$subject,$return_url){
		if(wp_is_mobile()){
			$url=$this->pay($sn,$price,$subject,$return_url);
			return ['type'=>'link','url'=>$url,'paytype'=>$this->paytype];
		}else{
			$url=$this->pay($sn,$price,$subject,$return_url);
			return ['type'=>'link','url'=>$url,'paytype'=>$this->paytype];
		}
	}
	
	function pay($sn,$price,$subject,$return_url){
		$data=array(
			'version'   => '1.1',//必须的，固定值，api 版本，目前暂时是1.1
			'plugins'   => $this->my_plugin_id,//可选，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+，会发到回调地址
			'appid'     => $this->appid, //必须的，APPID
			'trade_order_id'=> $sn, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
			'total_fee' => $price,//人民币，单位精确到分(测试账户只支持0.1元内付款)
			'title'     => $subject, //必须的，订单标题，长度32或以内
			'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
			'notify_url'=>  home_url('xhcallback'), //必须的，支付成功异步回调接口
			'return_url'=> $return_url,//可选，支付成功后的跳转地址
			'callback_url'=>Req::url(),//可选，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
			'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
		);
		$hashkey =$this->appsecret;
		$data['hash']     = xhapi::generate_xh_hash($data,$hashkey);
		$url              = 'https://api.xunhupay.com/payment/do.html';
		try {
			$response     = xhapi::http_post($url, json_encode($data));
			/**
			 * 支付回调数据
			 * @var array(
			 *      order_id,//支付系统订单ID
			 *      url//支付跳转地址
			 *  )
			 */
			$result       = $response?json_decode($response,true):null;
			if(!$result){
				throw new Exception('Internal server error',500);
			}

			$hash         = xhapi::generate_xh_hash($result,$hashkey);
			if(!isset( $result['hash'])|| $hash!=$result['hash']){
				throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
			}

			if($result['errcode']!=0){
				throw new Exception($result['errmsg'],$result['errcode']);
			}

			$pay_url =$result['url'];
			return $pay_url;
			//header("Location: $pay_url");
			exit;
		} catch (Exception $e) {
			echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";
			//TODO:处理支付调用异常的情况
		}
	}
	
}
