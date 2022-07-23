
//返回顶部
jQuery(function($){
$(window).scroll(function() {
        var scroll_len = $(window).scrollTop();
        if(scroll_len > 160) {
            $('.QZ-up').fadeIn();
        } else {
            $('.QZ-up').fadeOut();
        };
    });

    $('.QZ-up').click(function(e){
        $("body,html").animate({scrollTop:0},300);
    })
})

//侧边栏多张图
try{
	 var mySwiper = new Swiper ('.swiper-morimg', {
    direction: 'horizontal', 
    loop: true, // 循环模式选项
    autoplay:true,//true开启自动轮播
    
    // 如果需要前进后退按钮
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  }) 
}catch(err){}

try{
	$(document).ready(function() {
		try {
		$('.homebknav').theiaStickySidebar({
					additionalMarginTop: 15
				});
				} catch(err) {}
	
		try{
		$('.sidebar').theiaStickySidebar({
					additionalMarginTop: 15
				});
				} catch(err) {}
		});
		
}catch(err){}

//移动端导航
	$(document).ready(function(){
	  $(".mbtoggler").click(function(){
		$(".mbnav").animate({left:'0'});
	  });
	  $(".navclose").click(function(){
		$(".mbnav").animate({left:'-100%'});
	  });
	});
	
	//移动搜索
	$(document).ready(function(){
		$(".mb-navbtn.searchbtn").click(function(){
			$(".searchmod").fadeToggle("fast");
		});
	});
	
	  //点击空白处搜索框隐藏。
    $(document).click(function(event){
        var _con = $('.searchmod,.searchbtn');  // 设置目标区域
        if(!_con.is(event.target) && _con.has(event.target).length === 0){
        $('.header-m .searchmod').fadeOut("fast");//淡出消失
        }
   });
   
   try{
		$("#shareqr").qrcode({render:"canvas",width: 150,height:150,text:location.href});
	}catch(error){}
	
		$(".load-more").click(function(){
		var page=$(this).data('page');
		if($(".load-more").text()!="点击查看更多"){
			return;
		}
		$(".load-more").text("加载中……");
		var option={};
		var param={page:page,action:"index_load_posts"}
		option.success=function(res){
			if(res.code==1){
				$(".load-more").data("page",res.page);
				$(".load-more").parent().parent().prev().append(res.str);
				if(res.have_page==1){
					$(".load-more").text("点击查看更多");
				}else{
					$(".load-more-wrap").hide();
				}
			}else{
				letan.tip("获取内容失败");
			}
		}
		letan.call(ajaxurl,param,option);
	})