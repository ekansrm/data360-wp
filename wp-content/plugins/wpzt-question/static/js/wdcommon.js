//提问表单
	$(document).ready(function(){
	  $(".jstwbtn").click(function(){
		  var option={};
		  option.success=function(res){
			  if(res.code==0){
				  location.href=$("#loginurl").val();
			  }else if(res.code==1){
				  $(".modask").fadeIn();
			  }
		  }
		  var param={'action':'checklogin'};
		letan.call(ajaxurl,param,option);
	  });
	  $(".modaskclose").click(function(){
		$(".modask").fadeOut();
	  });
	});
	      //点击空白处隐藏弹出层。
    $(document).click(function(event){
        var _con = $('.modask-ctn,.jstwbtn');  // 设置目标区域
        if(!_con.is(event.target) && _con.has(event.target).length === 0){
        $('.modask').fadeOut("fast");//淡出消失
        }
   });
   var E = window.wangEditor;
 try{
  var editor2 = new E('.modask-edit');
      editor2.customConfig.uploadImgServer=ajaxurl;
	  editor2.customConfig.uploadImgMaxSize = 0.5 * 1024 * 1024; // 0.5M
	  editor2.customConfig.uploadImgAccept = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
	  editor2.customConfig.uploadImgParams = {action:'upload_wang_image'};
	  editor2.customConfig.uploadFileName = 'file';
	  editor2.customConfig.menus = [
        'head',  // 标题
		'bold',  // 粗体
		'fontSize',  // 字号
		'fontName',  // 字体
		'italic',  // 斜体
		'underline',  // 下划线
		'strikeThrough',  // 删除线
		'foreColor',  // 文字颜色
		'backColor',  // 背景颜色
		'link',  // 插入链接
		'image',  // 插入图片
		'video',  // 插入视频
    ]
	  editor2.customConfig.onchange = function (html) {
            // 监控变化，同步更新到 textarea
            $("#question_content").val(html)
        }
     editor2.create();
	 
 }catch(err){}
 
 function add_question(){
	 var title=$("#title").val();
	 if(title==""){
		 letan.tip('请填写提问标题');return;
	 }
	 var option={};
	 option.success=function(res){
		 if(res.code==1){
			 letan.tipreload(res.msg);
		 }else if(res.code==2){
			 location.href=$("#loginurl").val();
		 }else{
			 letan.tip(res.msg);
		 }
	 }
	 var param=$("#add_question").serialize();
	 letan.call(ajaxurl,param,option);
 }
 
  function del_comment(id){	//删除回答
	  var option={};
	  option.success=function(res){
		  letan.tipreload(res.msg);
	  }
	  var param={id:id,action:'delete_comment'}
	  letan.call(ajaxurl,param,option);
  }