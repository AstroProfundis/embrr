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
	checkbox('shownick',"#shownick",false);
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
			$.cookie('Bgcolor',"#" + hex,{expires:365});
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
			$.cookie('fontsize', '');
			$.cookie('Bgcolor', '');
			$.cookie('Bgimage','');
			$.cookie('showpic','true');
			$.cookie('shownick','false');
			$.cookie('mediaPre','true');
			$.cookie('p_avatar','false');
			$.cookie('homeInterval',1);
			$.cookie('updatesInterval',3);
			location.reload();
			updateSentTip('Setting Reset successfully!',3000,'success');
		}
	});
	
	$("textarea#myCSS").change(function(){
		$.cookie('myCSS',$(this).val(),{expires:365});
		location.reload();
		updateSentTip('Customized styles saved successfully!',3000,'success');
	});
	$("#AvatarUpload").click(function (e) {
		e.preventDefault();
		ProfileImageUpload();
	});
	$("#BackgroundUpload").click(function (e) {
		e.preventDefault();
		ProfileBackgroundUpload();
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

	$("#tile").click(function() {
		ProfileBackgroundTile($(this).prop('checked'));
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
				if (typeof(data.result) != 'undefined' && data.result == "success") {
					$.ajax({
						url: '../ajax/updateProfile.php',
						type: "GET",
						dataType: "json",
						success: function(msg){
							freshProfile();
							$("#avatarimg").attr("src",$.cookie("imgurl"));
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

function ProfileBackgroundUpload() {
	updateSentTip("Uploading your profile background...", 10000, "ing");
	$.ajaxFileUpload({
			url: 'ajax/uploadImage.php?do=background',
			timeout: 60000,
			secureuri: false,
			fileElementId: 'profile_background',
			dataType: 'json',
			success: function (data, status) {
				if (typeof(data.result) != 'undefined' && data.result == "success") {
					if ($.cookie('twitterbg') === 'true') {
						$.ajax({
							url:'ajax/updateProfile.php?extra=bg',
							dataType:'json',
							success: function() { location.reload(); }
						});
					}
					$("#backgroundimg").attr("src",data.url);
					updateSentTip("Your profile background has been uploaded!", 3000, "success");
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

function ProfileBackgroundTile(tile) {
	updateSentTip("Updating your profile background tile...", 3000, "ing");
	$.ajax({
			url: 'ajax/uploadImage.php?do=background',
			type: 'POST',
			data: {'tile': tile},
			dataType: 'json',
			success: function (data, status) {
				if (typeof(data.result) != 'undefined' && data.result == "success") {
					if ($.cookie('twitterbg') === 'true') {
						$.ajax({
							url:'ajax/updateProfile.php?extra=bg',
							dataType:'json',
							success: function() { location.reload(); }
						});
					}
					var isok = data.tile === 'true';
					if (isok != $("#tile").prop('checked')) {
						$("#tile").prop('checked', isok);
						updateSentTip("Failed to update, please try again.", 3000, "failure");
					}
					else {
						updateSentTip("Your profile background tile has been updated!", 3000, "success");
					}
				} else {
					updateSentTip("Failed to update, please try again.", 3000, "failure");
				}
			},
			error: function (data, status, e) {
				updateSentTip("Failed to update, please try again.", 3000, "failure");
			}
		})
	return false;
}
