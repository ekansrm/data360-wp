<?php
namespace wpzt\pay;

class Common{		//支付用
	function __construct(){
		
	}
	
	static function create_order_sn($prefix='sn'){	//创建订单号
		$code = date('ymdHis').sprintf("%08d", mt_rand(1, 99999999));
		if (!empty($prefix)) {
			$code = $prefix.substr($code, strlen($prefix));
		}
		return $code;
	}
	
	//用户ID 商品ID 类型 价格 @return ordersn//$type1资源2月会员3季度会员4年度会员5终身会员
	static function create_order($uid,$pid,$type,$price){
		global $wpdb;
		$add=[
			'sn'=>self::create_order_sn(),
			'uid'=>$uid,
			'pid'=>$pid,
			'type'=>$type,
			'status'=>0,
			'price'=>$price,
			'time'=>time()
		];
		if(wpzt('nologinpay')&&empty($uid)&&$pid!=0){//不是会员购买，开启了免登陆，用户没有登录则存入cookie
			$add['cookie']=\wpzt\form\Request::cookie('payuser');
		}
		$wpdb->insert($wpdb->prefix.'wpzt_order',$add);
		return $add['sn'];
	}
	
	static function del_order($oid){
		global $wpdb;
		if(is_numeric($oid)||empty($oid)){
			return false;
		}
		$where=['id'=>$oid];
		$flag=$wpdb->delete($wpdb->prefix.'wpzt_order',$where);
		return $flag;
	}
	
	static function update_order($order_sn,$pay_type,$pay_sn){
		global $wpdb;
		$where=['sn'=>$order_sn];
		$update=[
			'status'=>1,
			'paytype'=>$pay_type,
			'paysn'=>$pay_sn,
			'paytime'=>time()
		];
		$flag=$wpdb->update($wpdb->prefix.'wpzt_order',$update,$where);
		return $flag;
	}
	
	static function update_vip($uid,$type){	//更新用户VIP
		$lasttype=get_vip_type($uid);//wplog($lasttype);
		if($lasttype==4){
			return;
		}
		if(empty($lasttype)){
			switch($type){
				case 1:update_user_meta($uid,'vip',['type'=>1,'time'=>get_next_time(1)]);break;
				case 2:update_user_meta($uid,'vip',['type'=>2,'time'=>get_next_time(3)]);break;
				case 3:update_user_meta($uid,'vip',['type'=>3,'time'=>get_next_time(12)]);break;
				case 4:update_user_meta($uid,'vip',['type'=>4,'time'=>0]);break;
			}
		}else{
			$vip=get_user_meta($uid,'vip',true);
			$time=$vip['time'];
			$nowtype=$vip['type']>=$type?$vip['type']:$type;//当前和充值的会员比较
			switch($type){
				case 1:update_user_meta($uid,'vip',['type'=>$nowtype,'time'=>get_next_time(1,$time)]);break;
				case 2:update_user_meta($uid,'vip',['type'=>$nowtype,'time'=>get_next_time(3,$time)]);break;
				case 3:update_user_meta($uid,'vip',['type'=>$nowtype,'time'=>get_next_time(12,$time)]);break;
				case 4:update_user_meta($uid,'vip',['type'=>4,'time'=>0]);break;
			}
		}
	}
	
	function payed_order($sn,$paysn,$paytype){	//付款后处理订单(@sn订单编号@paysn付款编号paytype1支付宝，2微信3迅虎)
		global $wpdb;
		$order=$wpdb->get_row($wpdb->prepare("select * from {$wpdb->prefix}wpzt_order where sn=%s",$sn));
		//$pid=$order->pid;
		$uid=$order->uid;
		//$price=$order->price;
		$type=$order->type;
		self::update_vip($uid,$type);
		self::update_order($sn,$paytype,$paysn);
	}
	
	
	
}