<?php
header ("Content-type: image/png");
mb_internal_encoding("UTF-8"); // 设置编码
$excerpt = $_REQUEST['excerpt'] ? $_REQUEST['excerpt']:"未知内容";
$pic = $_REQUEST['pic'];
$logo = $_REQUEST['logo'];
$line = $_REQUEST['line'];
$title = $_REQUEST['title'] ? $_REQUEST['title']:"未知内容";
$timebg = $_REQUEST['timebg'];
$time = $_REQUEST['time'] ? $_REQUEST['time']:"未知内容";
$author = $_REQUEST['author'] ? $_REQUEST['author']:"未知内容";
$describe = $_REQUEST['describe'] ? $_REQUEST['describe']:"在【后台- 主题设置 - 文章页面】中设置";
$code = $_REQUEST['code'] ? $_REQUEST['code']:"http://relive.wpzt.cn/wp-content/themes/Relive/public/qrcode?data=http://www.wpzt.com";
//$str = "中华人民共和国";

function autowrap($fontsize, $angle, $fontface, $string, $width) {
// 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
 $content = "";

 // 将字符串拆分成一个个单字 保存到数组 letter 中
 for ($i=0;$i<mb_strlen($string);$i++) {
  $letter[] = mb_substr($string, $i, 1);
 }

 foreach ($letter as $l) {
  $teststr = $content." ".$l;
  $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
  // 判断拼接后的字符串是否超过预设的宽度
  if (($testbox[2] > $width) && ($content !== "")) {
   $content .= "\n";
  }
  $content .= $l;
 }
 return $content;
}


    $im = imagecreatetruecolor(720, 1280);
 
    //填充画布背景色
    $color = imagecolorallocate($im, 255, 255, 255);
    imagefill($im, 0, 0, $color);
 
    //字体文件
    $font_file = "./fonts/msyh.ttf";
    $font_file_bold = "./fonts/msyh_bold.ttf";
 
    //设定字体的颜色
    $font_color_1 = ImageColorAllocate ($im, 140, 140, 140);
    $font_color_2 = ImageColorAllocate ($im, 28, 28, 28);
    $font_color_3 = ImageColorAllocate ($im, 129, 129, 129);
    $font_color_4 = ImageColorAllocate ($im, 255, 255, 255);
 
    $fang_bg_color = ImageColorAllocate ($im, 254, 216, 217);

    //虚线
    list($l_w,$l_h) = getimagesize($line);
    $xuxianImg = @imagecreatefrompng($line);
    imagecopyresized($im, $xuxianImg, 0, 0, 0, 0, 720, 1280, $l_w, $l_h);

   //图片
    list($g_w,$g_h) = getimagesize($pic);
    $goodImg = createImageFromFile($pic);
    imagecopyresized($im, $goodImg, 0, 0, 0, 0, 720, 500, $g_w, $g_h);
 
    //标题
    $theTitle = autowrap(30, 0, $font_file, $title, 610);
    imagettftext($im, 30,0, 60, 600, $font_color_2 ,$font_file_bold, $theTitle);
	
	//副标题
	$theDescribe = cn_row_substr($describe,1,20);
    imagettftext($im, 17,0, 60, 1180, $font_color_1 ,$font_file, $theDescribe[1]); 
    
	//Logo
    list($l_w,$l_h) = getimagesize($logo);
    $logoImg = @imagecreatefrompng($logo);
    imagecopyresized($im, $logoImg, 60, 1090, 0, 0, 200, 50, $l_w, $l_h); 
 
 
    //二维码
    list($l_w,$l_h) = getimagesize($code);
    $logoImg = @imagecreatefrompng($code);
    imagecopyresized($im, $logoImg, 520, 1060, 0, 0, 150, 150, $l_w, $l_h); 
	
     //摘要
    $theTitle = autowrap(20, 0, $font_file, $excerpt, 610);
	imagettftext($im, 20,0, 60, 730, $font_color_1 ,$font_file, $theTitle);
   
 
 	//作者
    $theAuthor = cn_row_substr($author,1,20);
    imagettftext($im, 17,0, 60, 920, $font_color_3 ,$font_file_bold, $theAuthor[1]);
 

 
 	//时间背景
    list($l_w,$l_h) = getimagesize($timebg);
    $timebgImg = @imagecreatefrompng($timebg);
    imagecopyresized($im, $timebgImg, 40, 310, 0, 0, 130, 150, $l_w, $l_h);
 
 
   //时间
    $theTime = cn_row_substr($time,2,2.5);
    imagettftext($im, 23,0, 50, 447, $font_color_4 ,$font_file, $theTime[1]);
    imagettftext($im, 78,0, 20, 395, $font_color_4 ,$font_file, $theTime[2]);
 
//字体设置部分linux和windows的路径可能不同
header("Content-type:image/png");
imagepng($im);

function createImageFromFile($file){
    if(preg_match('/http(s)?:\/\//',$file)){
        $fileSuffix = getNetworkImgType($file);
    }else{
        $fileSuffix = pathinfo($file, PATHINFO_EXTENSION);
    }
 
    if(!$fileSuffix) return false;
 
    switch ($fileSuffix){
        case 'jpeg':
            $theImage = @imagecreatefromjpeg($file);
            break;
        case 'jpg':
            $theImage = @imagecreatefromjpeg($file);
            break;
        case 'png':
            $theImage = @imagecreatefrompng($file);
            break;
        case 'gif':
            $theImage = @imagecreatefromgif($file);
            break;
        default:
            $theImage = @imagecreatefromstring(file_get_contents($file));
            break;
    }
 
    return $theImage;
}
 
function getNetworkImgType($url){
    $ch = curl_init(); //初始化curl
    curl_setopt($ch, CURLOPT_URL, $url); //设置需要获取的URL
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);//设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //支持https
    curl_exec($ch);//执行curl会话
    $http_code = curl_getinfo($ch);//获取curl连接资源句柄信息
    curl_close($ch);//关闭资源连接
 
    if ($http_code['http_code'] == 200) {
        $theImgType = explode('/',$http_code['content_type']);
 
        if($theImgType[0] == 'image'){
            return $theImgType[1];
        }else{
            return false;
        }
    }else{
        return false;
    }
}
 
function cn_row_substr($str,$row = 1,$number = 10,$suffix = true){
    $result = array();
    for ($r=1;$r<=$row;$r++){
        $result[$r] = '';
    }
 
    $str = trim($str);
    if(!$str) return $result;
 
    $theStrlen = strlen($str);
 
    //每行实际字节长度
    $oneRowNum = $number * 3;
    for($r=1;$r<=$row;$r++){
        if($r == $row and $theStrlen > $r * $oneRowNum and $suffix){
            $result[$r] = mg_cn_substr($str,$oneRowNum-6,($r-1)* $oneRowNum).'...';
        }else{
            $result[$r] = mg_cn_substr($str,$oneRowNum,($r-1)* $oneRowNum);
        }
        if($theStrlen < $r * $oneRowNum) break;
    }
 
    return $result;
}
 
function mg_cn_substr($str,$len,$start = 0){
    $q_str = '';
    $q_strlen = ($start + $len)>strlen($str) ? strlen($str) : ($start + $len);
 
    //如果start不为起始位置，若起始位置为乱码就按照UTF-8编码获取新start
    if($start and json_encode(substr($str,$start,1)) === false){
        for($a=0;$a<3;$a++){
            $new_start = $start + $a;
            $m_str = substr($str,$new_start,3);
            if(json_encode($m_str) !== false) {
                $start = $new_start;
                break;
            }
        }
    }
 
    //切取内容
    for($i=$start;$i<$q_strlen;$i++){
        //ord()函数取得substr()的第一个字符的ASCII码，如果大于0xa0的话则是中文字符
        if(ord(substr($str,$i,1))>0xa0){
            $q_str .= substr($str,$i,3);
            $i+=2;
        }else{
            $q_str .= substr($str,$i,1);
        }
    }
    return $q_str;
}

?>