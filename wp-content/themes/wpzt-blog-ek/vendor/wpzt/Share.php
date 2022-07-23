<?php
namespace wpzt;

class Share{
	static function get_qq_url($url="",$title="",$pic="",$excerpt=""){
		return "https://connect.qq.com/widget/shareqq/index.html?url={$url}&sharesource=qzone&title={$title}&pics={$pic}&summary={$excerpt}&desc={$excerpt}";
	}
	
	static function get_qqz_url($url="",$title="",$pic="",$excerpt=""){
		return "https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url={$url}&sharesource=qzone&title={$title}&pics={$pic}&summary={$excerpt}";
	}
	
	static function get_weibo_url($url="",$title="",$pic=""){
		return "https://service.weibo.com/share/share.php?url={$url}&type=button&language=zh_cn&title={$title}&pic={$pic}&searchPic=true";
	}
	
	static function get_tieba_url($url="",$title="",$excerpt='',$pic=""){
		return urlencode("https://tieba.baidu.com/f/commit/share/openShareApi?url={$url}&title={$title}&desc={$excerpt}&comment=&pic={$pic}");
	}
}