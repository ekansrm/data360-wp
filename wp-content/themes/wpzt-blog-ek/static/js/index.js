try{
	var mySwiper = new Swiper ('.swiper-banner', {
		direction: 'horizontal', 
		loop: true, // 循环模式选项
		autoplay:true,//true开启自动轮播
		// 如果需要分页器
		pagination: {
		  el: '.swiper-pagination',
		  clickable :true,

		},
		
		// 如果需要前进后退按钮
		navigation: {
		  nextEl: '.swiper-button-next',
		  prevEl: '.swiper-button-prev',
		},
})}catch(error){}