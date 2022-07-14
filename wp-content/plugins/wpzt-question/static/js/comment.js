 var E = window.wangEditor
 var editor = new E('#editor')
 add_wang_menu(editor);
 add_wang_emotions(editor);
// set_wang_upload(editor);
 //set_wang_callback(editor);
 set_editor_content(editor);
 editor.create()
 
 
 function add_wang_menu(editor){//控制菜单
	 editor.customConfig.menus = [
        'bold',
        'italic',
		'fontSize',
		'foreColor',
		'emoticon',  // 表情
	//	'image',  
    ]
 }
 
 function add_wang_emotions(editor){
	 editor.customConfig.emotions= [{
            title: '默认',
            type: 'image',
			 content: [
				{
        src : facial+"shenshou_thumb.gif",
        alt : "[草泥马]"
    }, {
        src : facial+"horse2_thumb.gif",
        alt : "[神马]"
    }, {
        src : facial+"fuyun_thumb.gif",
        alt : "[浮云]"
    }, {
        src : facial+"geili_thumb.gif",
        alt : "[给力]"
    }, {
        src : facial+"wg_thumb.gif",
        alt : "[围观]"
    }, {
        src : facial+"vw_thumb.gif",
        alt : "[威武]"
    }, {
        src : facial+"panda_thumb.gif",
        alt : "[熊猫]"
    }, {
        src : facial+"rabbit_thumb.gif",
        alt : "[兔子]"
    }, {
        src : facial+"otm_thumb.gif",
        alt : "[奥特曼]"
    }, {
        src : facial+"j_thumb.gif",
        alt : "[囧]"
    }, {
        src : facial+"hufen_thumb.gif",
        alt : "[互粉]"
    }, {
        src : facial+"liwu_thumb.gif",
        alt : "[礼物]"
    }, {
        src : facial+"smilea_thumb.gif",
        alt : "[呵呵]"
    }, {
        src : facial+"tootha_thumb.gif",
        alt : "[嘻嘻]"
    }, {
        src : facial+"laugh.gif",
        alt : "[哈哈]"
    }, {
        src : facial+"tza_thumb.gif",
        alt : "[可爱]"
    }, {
        src : facial+"kl_thumb.gif",
        alt : "[可怜]"
    }, {
        src : facial+"kbsa_thumb.gif",
        alt : "[挖鼻屎]"
    }, {
        src : facial+"cj_thumb.gif",
        alt : "[吃惊]"
    }, {
        src : facial+"shamea_thumb.gif",
        alt : "[害羞]"
    }, {
        src : facial+"zy_thumb.gif",
        alt : "[挤眼]"
    }, {
        src : facial+"bz_thumb.gif",
        alt : "[闭嘴]"
    }, {
        src : facial+"bs2_thumb.gif",
        alt : "[鄙视]"
    }, {
        src : facial+"lovea_thumb.gif",
        alt : "[爱你]"
    }, {
        src : facial+"sada_thumb.gif",
        alt : "[泪]"
    }, {
        src : facial+"heia_thumb.gif",
        alt : "[偷笑]"
    }, {
        src : facial+"qq_thumb.gif",
        alt : "[亲亲]"
    }, {
        src : facial+"sb_thumb.gif",
        alt : "[生病]"
    }, {
        src : facial+"mb_thumb.gif",
        alt : "[太开心]"
    }, {
        src : facial+"ldln_thumb.gif",
        alt : "[懒得理你]"
    }, {
        src : facial+"yhh_thumb.gif",
        alt : "[右哼哼]"
    }, {
        src : facial+"zhh_thumb.gif",
        alt : "[左哼哼]"
    }, {
        src : facial+"x_thumb.gif",
        alt : "[嘘]"
    }, {
        src : facial+"cry.gif",
        alt : "[衰]"
    }, {
        src : facial+"wq_thumb.gif",
        alt : "[委屈]"
    }, {
        src : facial+"t_thumb.gif",
        alt : "[吐]"
    }, {
        src : facial+"k_thumb.gif",
        alt : "[打哈欠]"
    }, {
        src : facial+"bba_thumb.gif",
        alt : "[抱抱]"
    }, {
        src : facial+"angrya_thumb.gif",
        alt : "[怒]"
    }, {
        src : facial+"yw_thumb.gif",
        alt : "[疑问]"
    }, {
        src : facial+"cza_thumb.gif",
        alt : "[馋嘴]"
    }, {
        src : facial+"88_thumb.gif",
        alt : "[拜拜]"
    }, {
        src : facial+"sk_thumb.gif",
        alt : "[思考]"
    }, {
        src : facial+"sweata_thumb.gif",
        alt : "[汗]"
    }, {
        src : facial+"sleepya_thumb.gif",
        alt : "[困]"
    }, {
        src : facial+"sleepa_thumb.gif",
        alt : "[睡觉]"
    }, {
        src : facial+"money_thumb.gif",
        alt : "[钱]"
    }, {
        src : facial+"sw_thumb.gif",
        alt : "[失望]"
    }, {
        src : facial+"cool_thumb.gif",
        alt : "[酷]"
    }, {
        src : facial+"hsa_thumb.gif",
        alt : "[花心]"
    }, {
        src : facial+"hatea_thumb.gif",
        alt : "[哼]"
    }, {
        src : facial+"gza_thumb.gif",
        alt : "[鼓掌]"
    }, {
        src : facial+"dizzya_thumb.gif",
        alt : "[晕]"
    }, {
        src : facial+"bs_thumb.gif",
        alt : "[悲伤]"
    }, {
        src : facial+"crazya_thumb.gif",
        alt : "[抓狂]"
    }, {
        src : facial+"h_thumb.gif",
        alt : "[黑线]"
    }, {
        src : facial+"yx_thumb.gif",
        alt : "[阴险]"
    }, {
        src : facial+"nm_thumb.gif",
        alt : "[怒骂]"
    }, {
        src : facial+"hearta_thumb.gif",
        alt : "[心]"
    }, {
        src : facial+"unheart.gif",
        alt : "[伤心]"
    }, {
        src : facial+"pig.gif",
        alt : "[猪头]"
    }, {
        src : facial+"ok_thumb.gif",
        alt : "[ok]"
    }, {
        src : facial+"ye_thumb.gif",
        alt : "[耶]"
    }, {
        src : facial+"good_thumb.gif",
        alt : "[good]"
    }, {
        src : facial+"no_thumb.gif",
        alt : "[不要]"
    }, {
        src : facial+"z2_thumb.gif",
        alt : "[赞]"
    }, {
        src : facial+"come_thumb.gif",
        alt : "[来]"
    }, {
        src : facial+"sad_thumb.gif",
        alt : "[弱]"
    }, {
        src : facial+"lazu_thumb.gif",
        alt : "[蜡烛]"
    }, {
        src : facial+"cake.gif",
        alt : "[蛋糕]"
    }, {
        src : facial+"clock_thumb.gif",
        alt : "[钟]"
    }, {
        src : facial+"m_thumb.gif",
        alt : "[话筒]"
    }

			 ]
	 }];
 }
 
 function set_wang_upload(editor){//上传设置
	 editor.customConfig.uploadImgServer = ajaxurl;
	 editor.customConfig.uploadImgMaxSize = 512* 1024;
	 editor.customConfig.uploadImgMaxLength = 1;
	 editor.customConfig.uploadFileName = 'wang_img';
	 editor.customConfig.showLinkImg = false;
	 editor.customConfig.uploadImgParams={
		 action:'wangeditorupload' 
	 }
 }
 function set_wang_callback(editor){
	 editor.customConfig.uploadImgHooks = {
		 success: function (xhr, editor, res) {
			
		 },
		  fail: function (xhr, editor, res) {
			 if(res.errno==1){
				 alert('获取上传文件失败');
			 }
			 if(res.errno==2){
				 alert('上传文件格式必须为png,jpg,jpeg');
			 }
			 if(res.errno==3){
				 alert('文件要小于500K');
			 }
		},
	 }
 }
 
 function set_editor_content(editor){//内容复制到textarea
	 var editorarea = $('#comment');
        editor.customConfig.onchange = function (html) {
            editorarea.val(html);
        }
 }
 
 function send_comment(pid,parentid){
	 var option={};
	 option.success=function(res){
		 if(res.code==0){
			 letan.tip(res.msg);
		 }
		 if(res.code==1){
			 location.reload();
		 }
	 }
	 if(parentid!=0){
		 var content=$("#editorarea"+parentid).val();
	 }else{
		var content=$("#editorarea").val();
	 }
	 var param={'action':'sendcomment','content':content,'pid':pid,'parentid':parentid};
	 letan.call(ajaxurl,param,option);
 }
 
 function comment_ding(commentid){	//点赞操作
	 var option={};
	 option.success=function(res){
		 if(res.code==0){
			 letan.tip(res.msg);
		 }
		 if(res.code==1){
			 $("#zhan"+commentid).text(res.num);
		 }
	 }
	 var param={'action':'zhancomment','commentid':commentid};
	 letan.call(ajaxurl,param,option);
 }
 
 function replay(commentid,name){
	 if( $("#replayform"+commentid).css('display')=='block'){
		 $(".replayform").hide();
		 return;
	 }
	 $(".replayform").hide();
	 $("#replayform"+commentid).show();
	// var E=window.wangEditor;
	 var editor=new E('#editor'+commentid);
	  add_wang_menu(editor);
	  add_wang_emotions(editor);
	  set_wang_upload(editor);
	  set_wang_callback(editor);
	  set_editor_content1(editor,commentid);
	  editor.create()
	  editor.txt.html('<p>@'+name+'</p>');
	  $('#editorarea'+commentid).val(editor.txt.html());
 }
 
  function set_editor_content1(editor,commentid){//内容复制到textarea
	 var editorarea = $('#editorarea'+commentid);
        editor.customConfig.onchange = function (html) {
            editorarea.val(html);
        }
 }
 
 $("#commentform").validate({
	 rules:{
		 comment:{
			 required:true
		 }
	 },
	 messages:{
		 comment:{
			 required:'请填写评论内容'
		 }
	 }
	 
 });
 
 $(document).on('click',".comment-reply-button",function(){
	 if($(this).closest('.comment-body').find('.comment-inner').length!=0){
		 $(this).closest('.comment-body').find('.comment-inner').remove();
		 return;
	 }
	 if($("#userid").val()==0){
		 location.href=$("#loginurl").val();
	 }
	 if($(this).closest(".comment-body").find(".reply-btn").length>0){
		 return;
	 }
	 $('.reply-btn').parent().remove();
	// console.log($(this).closest(".comment-body").find(".reply-btn"));
	 var pid=$(this).data('postid');
	 var commentid=$(this).data('commentid');
	 var str="<div class='comment-inner'><div class='comment-author vcard'>";
	 var avatar="<img src='"+$("#avatar").val()+"'/>";
	 str+=avatar+"</div>";
	 str+="<div class='comment-body'><textarea id='myreply'></textarea></div><button data-postid='"+pid+"' data-commentid='"+commentid+"' type='button' class='reply-btn'>回复</button></div>";
	 $(this).closest(".comment-body").append(str);
 });
 
 $(document).on('click',".reply-btn",function(){
	
	 var pid=$(this).data('postid');
	 var commentid=$(this).data('commentid');
	 var content=$("#myreply").val();
	 var option={};
	 option.success=function(res){
		 if(res.code==1){
			 location.reload();
		 }else{
			 letan.tip(res.msg);
		 }
	 }
	 var param={pid:pid,commentid:commentid,content:content,action:'wpzt_add_comment'};
	 letan.call(ajaxurl,param,option);
 });
 
 $(document).on('click',".wpzt-childcount",function(){	//点击显示和隐藏下级评论
	 var count=$(this).data('count');
	 var show=$(this).data('show');
	 if(count==0){return;}
	 if(show==0){
		 $(this).data('show',1);
		 $(this).closest('li').find('ul').show();
	 }else{
		 $(this).data('show',0);
		 $(this).closest('li').find('ul').hide();
	 }
 })
 
 $(document).on("click",".wpzt-load-more",function(){
	 var page=$(this).data('page');
	 page=page+1;//请求页码
	 $(this).data('page',page);
	 var pagecount=$(this).data('pagecount');
	 var postid=$(this).data('pid');
	 if(page>=pagecount){
				 $(this).hide();
			 }
	 console.log(page);
	 var option={};
	 option.success=function(res){
		 if(res.code==0){
			 letan.tip(res.code);
		 }else if(res.code==1){	 
			 $(".comments-list").append(res.comment);
		 }
	 }
	 option.beforeSubmit=function(){
		 $(this).text('加载中...');
	 }
	 option.complete=function(){
		 $(this).text("加载更多");
	 }
	 var param={postid:postid,page:page,action:'get_page_comment'};
	 letan.call(ajaxurl,param,option);
 })