$(function () {
	$('ol.rank_list').click(function(e){
		var $this = $(e.target);
		switch(e.target.id) {
				//avatar menu
			case 'avatar':
				e.preventDefault();
				ulinit($this);
				e.stopPropagation();
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
			case 'ul_mute':
				e.preventDefault();
				ulmute($this);
			break;
			case 'ul_unmute':
				e.preventDefault();
				ulunmute($this);
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
	$('ul.right_menu').fadeOut('fast');
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
				if (r & 1) {
					html += '<li><a class="ul_unfollow" href="#"><i></i>Unfollow</a></li>';
				} else {
					html += '<li><a class="ul_follow" href="#"><i></i>Follow</a></li>';
				}
				if (r & 2) {
					html += '<li><a class="ul_dm" href="#"><i></i>Message</a></li>';
				}
				if (r & 4) {
					html += '<li><a class="ul_unblock" href="#"><i></i>Unblock</a></li>';
				} else {
					html += '<li><a class="ul_block" href="#"><i></i>Block</a></li>';
				}
				if (r & 8) {
					html += '<li><a class="ul_unmute" href="#"><i></i>Unmute</a></li>';
				} else {
					html += '<li><a class="ul_mute" href="#"><i></i>Mute</a></li>';
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
	var text = "@" + getid($this.parent()) + ' ';
	if ($("#textbox").length > 0) {
		var sentIdBox = $("#sent_id");
		if (sentIdBox.length > 0) {
			$('#statuses h2:first-of-type').html("Mention");
		}
	} else {
		$("#statuses h2").before('<h2>Mention</h2>' + formHTML);
		formFunc();
	}
	scroll(0, 0);
	$("#textbox").focus().val(text);
	leaveWord();
}
function uldm($this, e) {
	var replie_id = getid($this.parent());
	var dmTitle = 'Send direct message to <input type="text" style="border: 1px solid rgb(167, 166, 170); margin: 0px 0px 6px; padding: 2px; height: 14px; width: 120px; font-size: 13px;" name="sent_id" id="sent_id" value="' + replie_id + '">';
	if ($("#textbox").length > 0) {
		var sentIdBox = $("#sent_id");
		if (sentIdBox.length == 0) {
			$("#statuses h2:first-of-type").html(dmTitle);
		} else {
			$("#sent_id").val(replie_id);
		}
	} else {
		$("#statuses h2").before('<h2>' + dmTitle + '</h2>' + formHTML);
		formFunc();
	}
	scroll(0, 0);
	$("#textbox").focus().val('');
	leaveWord();
}
function ulmute($this) {
	var id = getid($this.parent());
	updateSentTip("Muting " + id + "...", 5000, "ing");
	$.ajax({
		url: "ajax/relation.php",
		type: "POST",
		data: "action=mute&id=" + id,
		success: function (msg) {
			if (msg.indexOf("success") >= 0) {
				$this.parent().parent().parent().addClass("reply");
				$this.removeClass().addClass("ul_unmute").html("<i></i>Unmute");
				updateSentTip("You have muted " + id + "!", 3000, "success");
			} else {
				updateSentTip("Failed to mute " + id + ", please try again.", 3000, "failure");
			}
		},
		error: function (msg) {
			updateSentTip("Failed to mute " + id + ", please try again.", 3000, "failure");
		}
	});
}
function ulunmute($this) {
	var id = getid($this.parent());
	updateSentTip("Unmuting " + id + "...", 5000, "ing");
	$.ajax({
		url: "ajax/relation.php",
		type: "POST",
		data: "action=unmute&id=" + id,
		success: function (msg) {
			if (msg.indexOf("success") >= 0) {
				$this.parent().parent().parent().addClass("reply");
				$this.removeClass().addClass("ul_mute").html("<i></i>Mute");
				updateSentTip("You have unmuted " + id + "!", 3000, "success");
			} else {
				updateSentTip("Failed to unmute " + id + ", please try again.", 3000, "failure");
			}
		},
		error: function (msg) {
			updateSentTip("Failed to unmute " + id + ", please try again.", 3000, "failure");
		}
	});
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
				$this.removeClass().addClass("ul_unfollow").html("<i></i>Unfollow");
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
					$this.removeClass().addClass("ul_follow").html("<i></i>Follow");
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
