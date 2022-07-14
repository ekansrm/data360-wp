<?php
namespace wpzt\form;

class Validate{
	public static function isEmail($email){
		//$regex= '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
		//return preg_match($regex,$email)；
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
		}else{
			return false;
		}
	}
	
	public static function isPhone($phone){
		$regex="/^1[3-9]{1}[0-9]{9}$/";
		return preg_match($regex,$phone);
	}
	public static function isLinephone($phone){
		$regex="/^([0-9]{3,4}-)?[0-9]{7,8}$/";
		return preg_match($regex,$phone);
	}
	
	public static function isInt($number){
		if(filter_var($number, FILTER_VALIDATE_INT)){
			return true;
		}else{
			return false;
		}
	}
	public static function isFloat($number){
		if(filter_var($number, FILTER_VALIDATE_FLOAT)){
			return true;
		}else{
			return false;
		}
	}
	public static function isUrl($url){
		if(filter_var($url, FILTER_VALIDATE_URL)){
			return true;
		}else{
			return false;
		}
	}
	public static function isIp($ip){
		if(filter_var($ip, FILTER_VALIDATE_IP)){
			return true;
		}else{
			return false;
		}
	}
	
}