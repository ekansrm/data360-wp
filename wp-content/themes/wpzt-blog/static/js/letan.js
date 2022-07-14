var letan={};		//定义对象

letan.formdata=function(form){	//表单数据转换为js对象
	var data = {};
    var fields = $(form).serializeArray();
    $.each(fields, function() {
    	if (data[this.name] !== undefined) {
            if (!data[this.name].push) {
            	data[this.name] = [data[this.name]];
            }
            data[this.name].push(this.value || '');
        } else {
        	data[this.name] = this.value || '';
        }
    });
    return data;
}

letan.numbertomoney=function(num){		//数字转换为两位小数
	 if(num!=null && !isNaN(num)){
        return parseFloat(num).toFixed(2);
    }
    return "0.00";
}

letan.refresh=function(){   //刷新网页
	location.reload();
}

letan.redirect=function(url){		//网页重定向
	location.href=url;
}

letan.iswechat=function(){			//判断是否为微信浏览器
	var ua = window.navigator.userAgent.toLowerCase(); 
	if(ua.match(/MicroMessenger/i) == 'micromessenger'){ 
	return true; 
	}else{ 
	return false; 
	} 
}

letan.call=function(url, params,options){		//ajax处理
	var option = {};
            option.validate = function(){
                return true;
            }
		option.beforeSubmit = function(){
               //console.log('BeforeSubmit >>>> do you need me show animation ?');
            };
            option.success = function(response){
              // console.log('SUCCESS >>>>'+response);
            };
            option.error = function(response){
            //  console.log('ERROR >>>>'+response);
            };
            option.complete = function(){
              // console.log('Complete >>>> do you need me close animation ?');
            };
            $.extend(option,options);
            //do validation
             if(!option.validate()){
                 return false;
             }
			 option.beforeSubmit();
			 $.ajax(url, {
				'dataType': 'json', // if you want to cross domain post jsonp
				'type':"POST",// if you want to cross domain post ,you should use GET
				'traditional': true,	// Server only supports traditional style params
				'data': params,
				'success': function(response, status, xhr){
                  // 跨域时，开启                   
                        option.success(response);
				},
				'error': function(xhr, status, error){
					var response = {};
					// Parse json response
					try{
						response = $.parseJSON(xhr.responseText);
					}catch(e){
						error = 'parse_error';
						response = 'parse json error!';
					}
					option.error(response);
				},
				'complete':function(res){
				   
				 option.complete();
				}//end complete
			});
}
 
letan.tip=function(txt){							//信息提示
		 times = 2500;
	 if($(".letantips").length > 0){
		return; 
	 }
		var tipsObj = $('<div style="z-index:9999; position:fixed;top:35%; width:100%;" class="letantips"><p style="text-align:center;width:280px; margin:0 auto; padding:10px 12px 10px; color:#f56c6c; text-align:center; font-size:14px; margin:0 auto; background:#fef0f0; border-radius:5px; line-height:18px;">'+ txt + '</p></div>');
		$("body").append(tipsObj);
		setTimeout(function(){
			$(".letantips").animate({opacity:0}, 500, function(){
				$(".letantips").remove();	
			});
		}, times);
	 } 
	 
 letan.tipreload=function(txt){			//提示后刷新
	  times = 2500;
	 if($(".letantips").length > 0){
		return; 
	 }
		var tipsObj = $('<div style="z-index:9999; position:fixed;top:25%; width:100%;" class="letantips"><p style="text-align:center;width:280px; margin:0 auto; padding:10px 12px 10px; color:#f56c6c; text-align:center; font-size:14px; margin:0 auto; background:#fef0f0; border-radius:5px; line-height:18px;">'+ txt + '</p></div>');
		$("body").append(tipsObj);
		setTimeout(function(){
			$(".letantips").animate({opacity:0}, 500, function(){
				$(".letantips").remove();	
				location.reload();
			});
		}, times);
 }
 
 letan.tipredirect=function(txt,url){		//提示后跳转
	  times = 2500;
	 if($(".letantips").length > 0){
		return; 
	 }
		var tipsObj = $('<div style="z-index:9999; position:fixed;top:25%; width:100%;" class="letantips"><p style="text-align:center;width:280px; margin:0 auto; padding:10px 12px 10px; color:#f56c6c; text-align:center; font-size:14px; margin:0 auto; background:#fef0f0; border-radius:5px; line-height:18px;">'+ txt + '</p></div>');
		$("body").append(tipsObj);
		setTimeout(function(){
			$(".letantips").animate({opacity:0}, 500, function(){
				$(".letantips").remove();	
				location.href=url;
			});
		}, times);
 }
 /***几个验证***/
 letan.isNumber=function(num){
	 var re = /^[0-9]+.?[0-9]*$/;
	 if (!re.test(num)) {
		 return false;
	 }else{
		 return true;
	 }
 }
 
 letan.isNull=function(data){
	 if(data == "" || data == undefined || data == null){
		 return true;
	 }else{
		 return false;
	 }
 }
 
 letan.isEmail=function(str){
	  var re=/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
	  if (re.test(str) != true) {
		return false;
	  }else{
		return true;
	  }
 }
 
 letan.isInt=function(num){
	 var re = /^[-+]?\d*$/;
	 if(!re.test(num)){
			return false;
		}else{
			return true;
		}
 }
 
 letan.isPhone=function(tel){
	 var re = /^1[3456789]\d{9}$/;
	 if(!re.test(tel)){
			return false;
		}else{
			return true;
		}
 }
 
 letan.isChinese=function(str){
	 var re= /^[\u0391-\uFFE5]+$/;
	 if(!re.test(str)){
		return false;
	}else{
		return true;
	}
 }
 window.letan = letan;