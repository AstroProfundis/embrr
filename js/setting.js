$(function(){
	checkbox('showpic',"#showpic",true);
	checkbox('mediaPre',"#mediaPreSelect",true);
	checkbox('p_avatar',"#proxifyAvatar",false,function(){
		var imgurl = $.cookie('imgurl');
		if(imgurl.indexOf('img.php') > -1) {
			imgurl = imgurl.substr(15);
		} else {
			imgurl = 'img.php?imgurl='+imgurl;
		}
		$.cookie('imgurl',imgurl,{expires:365});
		freshProfile();
	});
	checkbox('autoscroll',"#autoscroll",true);
	checkbox('sidebarscroll',"#sidebarscroll",true,function(){
		$(window).unbind('scroll',scroller);
	});
	checkbox('twitterbg',"#twitterbg",false,function(){
		if($.cookie('twitterbg') === 'true'){
			$.ajax({
				url:'ajax/updateProfile.php?extra=bg',
				dataType:'json',
				success: function (){
					location.reload();
				}
			});
		} else {
			$.cookie('Bgcolor', '');
			$.cookie('Bgimage','');
			$.cookie('Bgrepeat','no-repeat');
			location.reload();
		}
	});
	selectbox('homeInterval',"#homeInterval",function(){
		$.cookie('intervalChanged','true',{expires:365});
	});
	selectbox('updatesInterval',"#updatesInterval",function(){
		$.cookie('intervalChanged','true',{expires:365});
	});
	selectbox('fontsize',"#fontsize");
	$('.bg_input').ColorPicker({ 
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		},
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val("#" + hex);
			$(el).ColorPickerHide();
			$.cookie('bodyBg',"#" + hex,{expires:365});
			location.reload();
			updateSentTip('Setting saved successfully!',3000,'success');
		}
	}).bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});
	$('#reset_link').bind('click', function(e){
		e.preventDefault();
		if(confirm("You will lose all customized settings!")){
			$.cookie('myCSS', '/*default.css*/');
			$.cookie('theme','')
			$.cookie('fontsize', '');
			$.cookie('Bgcolor', '');
			$.cookie('Bgimage','');
			$.cookie('showpic','true');
			$.cookie('mediaPre','true');
			$.cookie('p_avatar','false');
			$.cookie('homeInterval',1);
			$.cookie('updatesInterval',3);
			location.reload();
			updateSentTip('Setting Reset successfully!',3000,'success');
		}
	});
	var style = {
		"Twitter Default":{theme:"/*default*/"}, 
		"Dark Rabr":{theme:"@import url(themes/1.css);"}, 
		"Monokai Python":{theme:"@import url(themes/2.css);"}, 
		"Old Times":{theme:"@import url(themes/3.css);"}, 
		"Pink":{theme:"@import url(themes/4.css);"},
		"Warm @lgsoltek":{theme:"@import url(themes/5.css);"},
		"Cold @lgsoltek":{theme:"@import url(themes/6.css);"},
		"Green":{theme:"@import url(themes/7.css);"},
		"Shine":{theme:"@import url(themes/8.css);"},
		"Flew":{theme:"@import url(themes/9.css);"},
		"Golden":{theme:"@import url(themes/10.css);"},
		"#red":{theme:"@import url(themes/11.css);"},
		"Storm":{theme:"@import url(themes/12.css);"},
		"City":{theme:"@import url(themes/13.css);"},
		"Cosmos":{theme:"@import url(themes/14.css);"},
		"Pride (Rainbow)":{theme:"@import url(themes/15.css); /* Have a gay day! */"},
		"Drop Bombs":{theme:"@import url(themes/16.css);"},
		"Minimal":{theme:"@import url(themes/minimal.css);"},
	};
	$.each(style, function (i,o) {
		$("#styleSelect").append('<option value="' + o.theme + '">' + i + '</option>');
	});
	var theme = $.cookie('theme') == undefined ? '/*default*/' : $.cookie('theme');
	$("#styleSelect").change(function(){
		var o =$(this).val();
		$.cookie('theme',o,{expires:365});
		$.cookie('Bgimage','');
		location.reload();
		updateSentTip('Themes Saved Successfully!',3000,'success');
	}).eq(0).val(theme);
	
	$("textarea#myCSS").change(function(){
		$.cookie('myCSS',$(this).val(),{expires:365});
		location.reload();
		updateSentTip('Themes saved successfully!',3000,'success');
	});
	$("#AvatarUpload").click(function (e) {
		e.preventDefault();
		ProfileImageUpload();
	});
	$("#saveProfile").click(function(e){
		e.preventDefault();
		$.ajax({
			url: 'ajax/updateProfile.php',
			type: 'POST',
			data: {
				'name': $('input[name="name"]').val(),
				'url' : $('input[name="url"]').val(),
				'location': $('input[name="location"]').val(),
				'description': $('textarea[name="description"]').text()
			},
			dataType: 'json',
			success: function(msg) {
				if (msg.result == 'success') {
					freshProfile();
					updateSentTip ('Profile updated successfully!',3000,'success');
				} else {
					updateSentTip ('Fail to update your profile, please try again',3000,'failure');
				}
			},
			error: function() {
				updateSentTip ('Fail to update your profile, please try again',3000,'failure');
			}
		});
	});
});
function checkbox(c,id,d,extra){
	var $id = $(id);
	if ($.cookie (c) === null) {
		$.cookie (c, d, { expires: 30 });
	} 
	$id.prop('checked', $.cookie (c) === 'true').click(function (){
		$.cookie(c,$id.prop("checked"),{expires:365});
		if (extra != undefined) extra();
		updateSentTip('Setting saved successfully!',1000,'success');
	});
}
function selectbox(c,id,extra){
	var $id = $(id);
	if($.cookie(c) != undefined){
		$id.eq(0).val($.cookie(c));
	}
	$id.change(function (){
		$.cookie(c,$id.find(':selected').val(),{expires:365});
		if (extra != undefined) extra();
		updateSentTip('Setting saved successfully!',1000,'success');
	});
}

function ProfileImageUpload() {
	updateSentTip("Uploading your profile image...", 10000, "ing");
	$.ajaxFileUpload({
			url: 'ajax/uploadImage.php?do=profile',
			timeout: 60000,
			secureuri: false,
			fileElementId: 'profile_image',
			dataType: 'json',
			success: function (data, status) {
				if (typeof(console) !== 'undefined' && console != null) {
					console.info(data);
				}
				if (typeof(data.result) != 'undefined' && data.result == "success") {
					$.ajax({
						url: '../ajax/updateProfile.php',
						type: "GET",
						dataType: "json",
						success: function(msg){
							freshProfile();
							$(".settings > img").attr("src",$.cookie("imgurl"));
						}
					});
					updateSentTip("Your profile image has been uploaded!", 3000, "success");
				} else {
					updateSentTip("Failed to upload, please try again.", 3000, "failure");
				}
			},
			error: function (data, status, e) {
				updateSentTip("Failed to upload, please try again.", 3000, "failure");
			}
		})
	return false;
}