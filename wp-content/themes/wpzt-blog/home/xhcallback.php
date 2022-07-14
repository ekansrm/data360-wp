<?php
use wpzt\pay\Xunhuapi as api;

$data = $_POST;
foreach ($data as $k=>$v){
    $data[$k] = stripslashes($v);
}
if(!isset($data['hash'])||!isset($data['trade_order_id'])){
   echo 'failed';exit;
}
if(!isset($data['plugins'])){
    echo 'failed';exit;
}

if($data['plugins']=='wpzt_hot_alipay'){
	$appid              = wpzt('xhalipay_appid');
	$appsecret  		=wpzt('xhalipay_key');
}elseif($data['plugins']=='wpzt_hot_wechat'){
	$appid              = wpzt('xhwechat_appid');
	$appsecret  		=wpzt('xhwechat_key');
}else{
	wp_die('failed');exit;
}
$appkey =$appsecret;
$hash =api::generate_xh_hash($data,$appkey);
if($data['hash']!=$hash){
    //签名验证失败
    echo 'failed';exit;
}

//商户订单ID
$ordersn =$data['trade_order_id'];
$paysn=$data['transaction_id'];
if($data['status']=='OD'){
  	//处理业务逻辑
	$common=new \wpzt\pay\Common();
	$common->payed_order($ordersn,$paysn,3);
}else{
   
}

echo 'success';
exit;