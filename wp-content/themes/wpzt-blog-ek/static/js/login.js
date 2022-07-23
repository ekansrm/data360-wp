function login(){	//登录
	if(!$("#login-form").valid()){
		return;
	}
	var option={};
	option.success=function(res){
		if(res.code==0){
			letan.tipreload(res.msg);
		}
		if(res.code==1){
			location.href=$("#redirect_to").val();
		}
	}
	var param={action:'user_login',name:$("#login_name").val(),pwd:$("#login_pwd").val(),nonce:$("#login_action_field").val(),code:$("#login_code").val()};
	letan.call(ajaxurl,param,option);
}

$("#login-form").validate({
	rules:{
		login_name:{
			required:true
		},
		login_pwd:{
			required:true
		},
		login_code:{
			required:true
		}
	},
	messages:{
		login_name:{
			required:'请填写用户名'
		},
		login_pwd:{
			required:'请填写密码'
		},
		login_code:{
			required:'请填写验证码'
		}
	}
	
});

function reg(){
	if(!$("#reg_form").valid()){
		return;
	}
	var option={};
	option.success=function(res){
		if(res.code==0){
			letan.tipreload(res.msg);
		}
		if(res.code==1){
			location.href=$("#redirect_to").val();
		}
	}
	var param={action:'user_reg',name:$("#reg_name").val(),email:$("#reg_email").val(),pwd:$("#reg_pwd").val(),repwd:$("#reg_repwd").val(),nonce:$("#reg_action_field").val(),code:$("#reg_code").val()};
	letan.call(ajaxurl,param,option);
}
$("#reg_form").validate({
	rules:{
		reg_name:{
			required:true
		},
		reg_email:{
			required:true,
			email:true
		},
		reg_pwd:{
			required:true
		},
		reg_repwd:{
			required:true,
			equalTo:"#reg_pwd"
		},
		reg_code:{
			required:true
		}
	},
	messages:{
		reg_name:{
			required:'请填写用户名'
		},
		reg_email:{
			required:'请填写邮箱',
			email:'请填写正确邮箱地址'
		},
		reg_pwd:{
			required:'请填写密码'
		},
		reg_repwd:{
			required:'请确认密码',
			equalTo:'两次输入的密码不同'
		},
		reg_code:{
			required:'请输入验证码'
		}
	}
});

function find(){
	if(!$("#find_form").valid()){
		return;
	}
	var option={};
	option.success=function(res){
		letan.tipreload(res.msg);
	}
	var param={action:'user_find',email:$("#find_email").val(),nonce:$("#find_action_field").val(),code:$("#find_code").val()};
	letan.call(ajaxurl,param,option);
}

$("#find_form").validate({
	rules:{
		find_email:{
			required:true,
			email:true
		},
		find_code:{
			required:true
		}
	},
	messages:{
		find_email:{
			required:'请输入邮箱',
			email:'请输入正确的邮箱'
		},
		find_code:{
			required:'请输入验证码'
		}
	}
});

function resetpwd(){
	if(!$("#resetpwd_form").valid()){
		return;
	}
	var option={};
	option.success=function(res){
		if(res.code==0){
			letan.tip(res.msg);
		}
		if(res.code==1){
			letan.tipredirect(res.msg,$("#homeurl").val());
		}
	}
	var param={action:'user_resetpwd',email:$("#email").val(),pwd:$("#old_pwd").val(),reset_pwd:$("#reset_pwd").val(),reset_repwd:$("#reset_repwd").val(),nonce:$("#resetpwd_action_field").val()};
	letan.call(ajaxurl,param,option);
}
$("#resetpwd_form").validate({
	rules:{
		reset_pwd:{
			required:true
		},
		reset_repwd:{
			required:true,
			equalTo:"#reset_pwd"
		}
	},
	messages:{
		reset_pwd:{
			required:'请填写新密码'
		},
		reset_repwd:{
			required:'请填写确认密码',
			equalTo:'两次密码输入不同'
		}
	}
});

///////////个人中心//////////////
function changeavatar(){
		var file=$("#avatar")[0].files[0];
		if(window.FileReader){
			var fr=new FileReader();
			fr.onloadend=function(e){
				$("#avatarimg1").attr({'src':e.target.result});
				//$("#avatarimg2").attr({'src':e.target.result});
				//$("#avatarimg3").attr({'src':e.target.result});
			}
			fr.readAsDataURL($("#avatar")[0].files[0]);
		}
		
		var formData = new FormData();
		formData.append('photo', file);
		formData.append('action','uploadavatar');
		$.ajax({
                    type: "POST",
                    url:ajaxurl,
                    data:formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if(res){
						// res=$.parseJSON(res);
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
			letan.tip(res.msg);
		}
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
