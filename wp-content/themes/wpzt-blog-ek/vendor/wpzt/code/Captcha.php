<?php
namespace wpzt\code;

/*
	require_once(dirname(__FILE__)."/../../../../wp-load.php"); // 目录结构，如果是按照我这种目录的话则不用修改
    $font = get_template_directory()."/static/fonts/1.ttf"; // 引入验证码的字体文件，后面给出下载地址
	Header("Content-type: image/GIF");
    $imagecode=new  wpzt\Captcha(120,46,4,'','',$font);//参数1宽2高3字符数4session的key 4随机字符集5字符的字符文件
    $imagecode->imageout();
*/
class Captcha{
        private $width ;
        private $height;
        private $counts;
        private $distrubcode;
        private $fonturl;
        private $session;
		private $codenum;
        function __construct($width = 120,$height = 30,$counts = 5,$codenum,$distrubcode,$fonturl){
            $this->width=$width;
            $this->height=$height;
            $this->counts=$counts;
            $this->distrubcode=empty($distrubcode)?"1235467890qwertyuipkjhgfdaszxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM":$distrubcode;
            $this->fonturl=$fonturl;
			$this->codenum=empty($codenum)?'imgcode':$codenum.'code';
            $this->session=$this->sessioncode();
			
			$_SESSION[$this->codenum]=$this->session;//设置验证码session,注意可以更改session的key
			
        }
 
         public function imageout(){
            $im=$this->createimagesource();
            $this->setbackgroundcolor($im);
            $this->set_code($im);
            //$this->setdistrubecode($im);
            ImageGIF($im);
            ImageDestroy($im); 
        }
 
        private function createimagesource(){
            return imagecreate($this->width,$this->height);
        }
        private function setbackgroundcolor($im){
            $bgcolor = ImageColorAllocate($im, rand(200,255),rand(200,255),rand(200,255));
            imagefill($im,0,0,$bgcolor);
        }
        private function setdistrubecode($im){
            $count_h=$this->height;
            $cou=floor($count_h*2);
            for($i=0;$i<$cou;$i++){
                $x=rand(0,$this->width);
                $y=rand(0,$this->height);
                $jiaodu=rand(0,360);
                $fontsize=rand(4,6);
                $fonturl=$this->fonturl;
                $originalcode = $this->distrubcode;
                $countdistrub = strlen($originalcode);
                $dscode = $originalcode[rand(0,$countdistrub-1)];
                $color = ImageColorAllocate($im, rand(40,140),rand(40,140),rand(40,140));
                imagettftext($im,$fontsize,$jiaodu,$x,$y,$color,$fonturl,$dscode);
            }
        }
        private function set_code($im){
            $width=$this->width;
            $counts=$this->counts;
            $height=$this->height;
            $scode=$this->session;
            $y=floor($height/2)+floor($height/4);
            $fontsize=rand(20,25);
            $fonturl=$this->fonturl;
 
            $counts=$this->counts;
            for($i=0;$i<$counts;$i++){
                $char=$scode[$i];
                $x=floor($width/$counts)*$i+8;
                $jiaodu=rand(-20,30);
                $color = ImageColorAllocate($im,rand(0,50),rand(50,100),rand(100,140));
                imagettftext($im,$fontsize,$jiaodu,$x,$y,$color,$fonturl,$char);
            }
        }
        private function sessioncode(){
            $originalcode = $this->distrubcode;
            $countdistrub = strlen($originalcode);
            $_dscode = "";
            $counts=$this->counts;
            for($j=0;$j<$counts;$j++){
                $dscode = $originalcode[rand(0,$countdistrub-1)];
                $_dscode.=$dscode;
            }
            return $_dscode;
        }
}