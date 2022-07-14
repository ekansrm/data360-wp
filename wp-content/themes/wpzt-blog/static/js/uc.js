	function changeavatar(){
		var file=$("#avatar")[0].files[0];
		if(window.FileReader){
			var fr=new FileReader();
			fr.onloadend=function(e){
				$("#avatarimg").attr({'src':e.target.result});
				$("#top_avatar").attr({'src':e.target.result});
			}
			fr.readAsDataURL($("#avatar")[0].files[0]);
		}
		
		var formData = new FormData();
		formData.append('avatar', file);
		formData.append('action','uploadavatar');
		$.ajax({
                    type: "POST",
                    url:ajaxurl,
                    data:formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if(res){
                          if(res.code==0){
							  letan.tip(res.msg);
						  }  
                        }
                    }
                });
	}
	
	function changecover(){
		var file=$("#cover")[0].files[0];
		if(window.FileReader){
			var fr=new FileReader();
			fr.onloadend=function(e){
				$("#coverimg").attr({'src':e.target.result});
			}
			fr.readAsDataURL($("#cover")[0].files[0]);
		}
		
		var formData = new FormData();
		formData.append('cover', file);
		formData.append('action','uploadcover');
		$.ajax({
                    type: "POST",
                    url:ajaxurl,
                    data:formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if(res){
                          if(res.code==0){
							  letan.tip(res.msg);
						  }  
                        }
                    }
                });
	}
	
	
	
	function change_displayname(){
		var displayname=$("#displayname").val();
		if(!displayname){
			letan.tip('请填写昵称');return;
		}
		var param={'displayname':displayname,'action':'changedisplayname'};
		var option={};
		option.success=function(res){
			letan.tip(res.msg);
		}
		letan.call(ajaxurl,param,option);
	}
	function  changepwd(){	//修改密码
		var oldpwd=$("#oldpwd").val();
		var newpwd=$("#newpwd").val();
		var renewpwd=$("#renewpwd").val();
		if(newpwd!=renewpwd){
			letan.tip("两次输入的密码不同");return;
		}
		var param={'oldpwd':oldpwd,'newpwd':newpwd,'renewpwd':renewpwd,'action':'changepwd'};
		var option={};
		option.success=function(res){
			letan.tipreload(res.msg);
		}
		letan.call(ajaxurl,param,option);
	}
	
		
  
		
	function bindphone(){	//绑定手机
			var phone=$("#bindphone").val();
			var code=$("#bindphone_code").val();
			var pwd=$("#bindphone_pwd").val();
			var pattern=/1[3|4|5|6|7|8|9][0-9]{9}/;
			if(!pattern.test(phone)){
				letan.tip('手机号不正确');return;
			}
			if(!code){
				letan.tip('请填写验证码');return;
			}
			if(pwd&&pwd.length<6){
				letan.tip('请填写大于6个字符的密码');return;
			}
			var option={};
			option.success=function(res){
				letan.tip(res.msg);
			}
			var param={'phone':phone,'code':code,'pwd':pwd,'action':'bindphone'};
			letan.call(ajaxurl,param,option);
		}

	$(function(){
		$(".meansedit").click(function(){
			if($(this).data('status')==0){
				$(this).data('status',1);
				$(this).html("收起<i class='iconfont icon-double-arrow-up-'></i>");
				$(this).parent().siblings(".meansitem-ctn").css("display","block");
			}else{
				$(this).data('status',0);
				$(this).html("编辑<i class='iconfont icon-double-arrow-right-'></i>");
				$(this).parent().siblings(".meansitem-ctn").css("display","none");
			}
		})
		
	})
	
	function unbind_login(type){//解除登录授权
			var option={};
			option.success=function(res){
				if(res.code==0){
					letan.tip(res.msg);
				}
				if(res.code==1){
					location.reload();
				}
			}
			var param={action:'unbind_login',type:type};;
			letan.call(ajaxurl,param,option);
	}
	
	function social_login(type){
		var option={};
		option.success=function(res){
			if(res.code==0){
				letan.tip(res.msg);
			}
			if(res.code==1){
				location.href=res.url;
			}
		}
		var param={action:'social_auth',type:type};
		letan.call(ajaxurl,param,option);
	}
	
	
	
	
	$("#ep_imgfile").on("change",function(event){//保存文档页面的
		var reader = new FileReader();
				reader.onload=function(e){
					var img_src = reader.result;
					$("#ep-img").attr('src',img_src);
				}
				reader.readAsDataURL(this.files[0])
		
	})
	
	$(function(){
		$(".user-articles-filterbtn").click(function(){
			$(".user-articles-filterul").slideToggle("fast");
		});
	})
	
	function save_post(){
		 	if(letan.isNull($("#title").val())){
				letan.tip('请填写文章标题');return;
			}
		  if ($(".wp-editor-area").length > 0) {	//ajax上传内容用到，极其重要
			var wp_editor_id = $(".wp-editor-area").attr('id');
			var editor = tinyMCE.get(wp_editor_id);
			if (editor) {
			  var  content = editor.getContent();
			} else {
			  var  content = $('#' + wp_editor_id).val();
			}
			$("#post_content").val(content);
		}
		 
		if(letan.isNull($("#post_content").val())){
			letan.tip('请填写内容描述');return;
		}
		var option={};
		option.success=function(res){
			if(res.code==1){
				letan.tipreload(res.msg);
			}else{
				letan.tip(res.msg);
			}
		}
		var param=$("#post").serialize();
		letan.call(ajaxurl,param,option);
	}
	
	function delete_post(pid){
		var option={};
		option.success=function(res){
			letan.tipload(res.msg);
		}
		var param={pid:pid,action:'delete_post'};
		letan.call(ajaxurl,param,option);
	}