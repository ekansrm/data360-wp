<?php
namespace wpzt\code;

use Toplan\PhpSms\Sms;
class Sendsms{
	private $smstype;
	function __construct(){
		$this->smstype=1;//暂时使用阿里云
	}
	
	public function sendsmscode($to,$key='smscode'){
		switch ($this->smstype){
			case 1: return $this->sendalicode($to,$key);
			case 2: return $this->sendqqcode($to,$key);
			default:return false;
		}
	}
	
	private function sendalicode($to,$key){
		 $ali_template=wpzt('ali_template');
		 $smscode=$this->getcode();
		 $tempData=['code'=>$smscode];
		 session_start();
		 $_SESSION[$key]=$smscode;
		 $templates = [
				'Aliyun' => $ali_template
			]; 
		return $this->sendalisms($to,$templates,$tempData);
	}
	
	private function sendalisms($to,$templates,$tempData){	//发送阿里短信
		  $ali_accessKeyId=wpzt('ali_accessKeyId');
		  $ali_accessKeySecret=wpzt('ali_accessKeySecret');
		  $ali_sign=wpzt('ali_sign');
		  Sms::config(['Aliyun' => [
            'accessKeyId'       => $ali_accessKeyId,
            'accessKeySecret'   => $ali_accessKeySecret,
            'signName'          =>  $ali_sign,
            'regionId'          => 'cn-hangzhou',
         ]]);
		  Sms::scheme(['Aliyun' => '100 backup']);
		 $res=Sms::make()->to($to)->template($templates)->data($tempData)->send();
		 return $res['success'];
	}
	
	public function sendqqcode($to,$key='smscode'){
		 $qq_template=wpzt('qq_template');
		 $smscode=$this->getcode();
		 $tempData=['code'=>$smscode];
		 session_start();
		 $_SESSION[$key]=$smscode;
		 $templates = [
				'Qcloud' => $ali_template
			]; 
		 return $this->sendqqsms($to,$templates,$tempData);
	}
	
	private function sendqqsms($to,$templates,$tempData){
		 $qq_appId=wpzt('qq_appId');
		 $qq_appKey=wpzt('qq_appKey');
		 Sms::config(['Qcloud' => [
            'appId'     => $qq_appId,
            'appKey'    => $qq_appKey,
         ]]);
		 Sms::scheme(['Qcloud' => '100 backup']);
		 $res=Sms::make()->to($to)->template($templates)->data($tempData)->send();
	}
	
	private function getcode(){	//获取验证码
		$smscode=substr(strval(rand(1000000,9999999)),1);
		return $smscode;
	}
	
	public function checkcode($code,$key='smscode'){	//检验验证码
		return $code==$_SESSION[$key];
	}
}