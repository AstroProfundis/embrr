$(function () {
	$('ol.rank_list').click(function(e){
		var $this = $(e.target);
		switch(e.target.id) {
				//avatar menu
			case 'avatar':
				e.preventDefault();
				ulinit($this);
			break;
		}
		switch(e.target.className) {
			//avatar_menu_action
			case 'ul_mention':
				e.preventDefault();
				ulmention($this,e);
			break;
			case 'ul_dm':
				e.preventDefault();
				uldm($this,e);
			break;
			case 'ul_follow':
				e.preventDefault();
				ulfollow($this);
			break;
			case 'ul_unfollow':
				e.preventDefault();
				ulunfollow($this);
			break;
			case 'ul_block':
				e.preventDefault();
				ulblock($this);
			break;
			case 'ul_unblock':
				e.preventDefault();
				ulunblock($this);
			break;
			case 'ul_spam':
				e.preventDefault();
				ulspam($this);
			break;
		}
	});
})
function ulinit($this) {
	var $that = $this.parent().parent();
	var ul = $that.find(".right_menu");
	if (ul.length>0) {
		ul.fadeIn('fast');
	} else {
		var id = getid($this);
		$that.addClass("loading");
		$.ajax({
			url: 'ajax/relation.php',
			type: "POST",
			data: "action=show&id=" + id,
			success: function(msg){
				var html = '<ul class="right_menu round"><li><a class="ul_mention" href="#"><i></i>Mention</a></li>';
				var r = parseInt(msg);
				switch(r){
					case 1:
					html += '<li><a class="ul_dm" href="#"><i></i>Message</a></li>';
					case 2:
					html += '<li><a class="ul_unfollow" href="#"><i></i>Unfollow</a></li><li><a class="ul_block" href="#"><i></i>Block</a></li>';
					break;
					case 3:
					html += '<li><a class="ul_dm" href="#"><i></i>Message</a></li>';
					case 9:
					html += '<li><a class="ul_follow" href="#"><i></i>Follow</a></li><li><a class="ul_block" href="#"><i></i>Block</a></li>';
					break;
					case 4:
					html += '<li><a class="ul_follow" href="#"><i></i>Follow</a></li><li><a class="ul_unblock" href="#"><i></i>UnBlock</a></li>';
					break;
				}
				html += '<li><a class="ul_spam" href="#"><i></i>Report Spam</a></li><li><a href="user.php?id='+id+'">View Full Profile</a></ul>';
				$this.parent().after(html);
				$(html).fadeIn('fast');
				$that.removeClass("loading");
			},
			error: function(){
				return;
			}
		});	
	}
}
function ulmention($this, e) {
	var replie_id = getid($this.parent());;
	if ($("#textbox").length > 0) {
		var text = "@" + replie_id;
		scroll(0, 0);
		$("#textbox").focus().val($("#textbox").val() + text + ' ');
		leaveWord();
	} else {
		$("#statuses h2").before('<h2>Mention</h2>' + formHTML);
		formFunc();
		var text = "@" + replie_id;
		scroll(0, 0);
		$("#textbox").focus().val($("#textbox").val() + text + ' ');
		leaveWord();
	}
}
function uldm($this, e) {
	var replie_id = getid($this.parent());
	if ($("#textbox").length > 0) {
		var text = "D " + replie_id;
	} else {
		$("#statuses h2").before('<h2>Send direct message</h2>' + formHTML);
		formFunc();
		var text = "D " + replie_id;
	}
	scroll(0, 0);
	$("#textbox").focus().val($("#textbox").val() + text + ' ');
	leaveWord();
}
function ulfollow($this) {
	var id = getid($this.parent());
	updateSentTip("Following " + id + "...", 5000, "ing");
	$.ajax({
		url: "ajax/relation.php",
		type: "POST",
		data: "action=create&id=" + id,
		success: function (msg) {
			if (msg.indexOf("success") >= 0) {
				$this.parent().parent().parent().addClass("reply");
				updateSentTip("You have followed " + id + "!", 3000, "success");
			} else {
				updateSentTip("Failed to follow " + id + ", please try again.", 3000, "failure");
			}
		},
		error: function (msg) {
			updateSentTip("Failed to follow " + id + ", please try again.", 3000, "failure");
		}
	});
}
function ulunfollow($this) {
	var id = getid($this.parent());;
	if (confirm("Are you sure to unfollow " + id + " ?")) {
		updateSentTip("Unfollowing " + id + "...", 5000, "ing");
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=destory&id=" + id,
			success: function (msg) {
				if (msg.indexOf("success") >= 0) {
					$this.parent().parent().parent().addClass("filter");
					updateSentTip("You have unfollowed " + id + "!", 3000, "success");
				} else {
					updateSentTip("Failed to unfollow " + id + ", please try again.", 3000, "failure");
				}
			},
			error: function (msg) {
				updateSentTip("Failed to unfollow " + id + ", please try again.", 3000, "failure");
			}
		});
	}
}
function ulblock($this) {
	var id = getid($this.parent());;
	if (confirm("Are you sure to block " + id + " ?")) {
		updateSentTip("Blocking " + id + "...", 5000, "ing");
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=block&id=" + id,
			success: function (msg) {
				if (msg.indexOf("success") >= 0) {
					$this.parent().parent().parent().fadeOut("normal");
					updateSentTip("You have blocked " + id + "!", 3000, "success");
				} else {
					updateSentTip("Failed to block " + id + ", please try again.", 3000, "failure");
				}
			},
			error: function (msg) {
				updateSentTip("Failed to block " + id + ", please try again.", 3000, "failure");
			}
		});
	}
}
function ulunblock($this) {
	var id = getid($this.parent());;
	if (confirm("Are you sure to unblock " + id + " ?")) {
		updateSentTip("Unblocking " + id + "...", 5000, "ing");
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=unblock&id=" + id,
			success: function (msg) {
				if (msg.indexOf("success") >= 0) {
					$this.parent().parent().parent().fadeOut("normal");
					updateSentTip("You have unblocked " + id + "!", 3000, "success");
				} else {
					updateSentTip("Failed to unblock " + id + ", please try again.", 3000, "failure");
				}
			},
			error: function (msg) {
				updateSentTip("Failed to unblock " + id + ", please try again.", 3000, "failure");
			}
		});
	}
}
function ulspam($this) {
	var id = getid($this.parent());
	if (confirm("Are you sure to report " + id + " ?")) {
		updateSentTip("Reporting " + id + " as a spammer...", 5000, "ing");
		$.ajax({
			url: "ajax/reportSpam.php",
			type: "POST",
			data: "spammer=" + id,
			success: function (msg) {
				if (msg.indexOf("success") >= 0) {
					$this.parent().parent().parent().fadeOut("normal");
					updateSentTip("Successfully reported!", 3000, "success");
				} else {
					updateSentTip("Failed to report " + id + ", please try again.", 3000, "failure");
				}
			},
			error: function (msg) {
				updateSentTip("Failed to report " + id + ", please try again.", 3000, "failure");
			}
		});
	}
}
var getid = function ($this) {
	return $this.parent().parent().find(".rank_screenname").text();
}
$(document).ready(function(){
	$.ajax({
		url: '../ajax/updateProfile.php',
		type: "GET",
		success: function(){
			freshProfile();
		}
	});
});