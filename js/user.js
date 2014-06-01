$(function(){
	$("#allTimeline").click(function(e) {
		var $this = $(e.target);
		var matches = ($this.attr('class') || '').match(/\w+_btn/);
		var type = matches ? matches[0] : '';
		switch(type) {
			case 'rt_btn':
				e.preventDefault();
				if ($("#textbox").length > 0) {
					onRT($this);
				} else {
					$("#info_head").after('<h2>What\'s happening?</h2>' + formHTML);
					formFunc();
					onRT($this);
				}
				break;
			case 'retw_btn':
				e.preventDefault();
				onNwRT($this);
				break;
			case 'replie_btn':
				e.preventDefault();
				var replie_id = $this.parent().parent().find(".status_word").find(".user_name").attr("id");
				if ($("#textbox").length > 0) {
					onReplie($this,e);
				} else {
					$("#info_head").after('<h2>In reply to ' + replie_id + '</h2>' + formHTML);
					formFunc();
					onReplie($this,e);
				}
				break;
			case 'favor_btn':
				e.preventDefault();
				onFavor($this);
				break;
			case 'unfav_btn':
				e.preventDefault();
				UnFavor($this);
				break;
			case 'unrt_btn':
				e.preventDefault();
				onUndoRt($this);
				break;
		}
	});

	$("#info_reply_btn").click(function(){
		var replie_id = $("#info_name").text();
		if ($("#textbox").length == 0) {
			$("#info_head").after('<h2>In reply to ' + replie_id + '</h2>' + formHTML);
			formFunc();
		}
		$("#textbox").val($("#textbox").val() + "@" + replie_id + " ");
		$("#textbox").focus();
		leaveWord();
	});
	if ($.cookie("infoShow") == "hide") {
		onHide();
	}
	$("#info_hide_btn").click(function(){
		onHide();
	});

	$("#info_follow_btn").click(function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $("#info_name").text();
		updateSentTip("Following " + id + "...", 5000, "ing");
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=create&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					updateSentTip("You have followed " + id + "!", 3000, "success");
					$this.after('<a class="btn btn-red" id="info_block_btn" href="javascript:void(0)">Unfollow</a>');
					$this.remove();
					if($('#unblock') != null){
						$('#unblock').after('<a class="btn" id="block_btn" href="javascript:void(0)">Block</a>');
						$('#unblock').remove();
					}
				} else {
					updateSentTip("Failed to follow " + id + ", please try again.", 3000, "failure");
				}
			},
			error: function(msg) {
				updateSentTip("Failed to follow " + id + ", please try again.", 3000, "failure");
			}
		});
	});

	$("#info_block_btn").click(function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $("#info_name").text();
		if (confirm("Are you sure to unfollow " + id + " ?")) {
		updateSentTip("Unfollowing " + id + "...", 5000, "ing");
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=destory&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					updateSentTip("You have unfollowed " + id + "!", 3000, "success");
					$this.after('<a class="btn btn-green" id="info_follow_btn" href="javascript:void(0)">Follow</a>');
					$this.remove();
				} else {
					updateSentTip("Failed to unfollow " + id + ", please try again.", 3000, "failure");
				}
			},
			error: function(msg) {
				updateSentTip("Failed to unfollow " + id + ", please try again.", 3000, "failure");
			}
		});
		}
	});

	$("#block_btn").click(function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $("#info_name").text();
		if (confirm("Are you sure to block " + id + " ?")) {
			updateSentTip("Blocking " + id + "...", 5000, "ing");
			$.ajax({
				url: "ajax/relation.php",
				type: "POST",
				data: "action=block&id=" + id,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						updateSentTip("You have blocked " + id + "!", 3000, "success");
						$this.after('<a class="btn" id="unblock_btn" href="javascript:void(0)">Unblock</a>');
						$this.remove();
						if($('#info_block_btn') != null){
							$('#info_block_btn').after('<a class="btn btn-green" id="info_follow_btn" href="javascript:void(0)">Follow</a>');
							$('#info_block_btn').remove();
						}
					} else {
						updateSentTip("Failed to block " + id + ", please try again.", 3000, "failure");
					}
				},
				error: function(msg) {
					updateSentTip("Failed to block " + id + ", please try again.", 3000, "failure");
				}
			});
		}
	});

	$("#unblock_btn").click(function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $("#info_name").text();
		if (confirm("Are you sure to unblock " + id + " ?")) {
			updateSentTip("Unblocking...", 5000, "ing");
			$.ajax({
				url: "ajax/relation.php",
				type: "POST",
				data: "action=unblock&id=" + id,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						updateSentTip("Unblocked.", 3000, "success");
						$this.after('<a class="btn" id="block_btn" href="javascript:void(0)">Block</a>');
						$this.remove();
					} else {
						updateSentTip("Failed to unblock, please try again.", 3000, "failure");
					}
				},
				error: function(msg) {
					updateSentTip("Failed to unblock, please try again.", 3000, "failure");
				}
			});
		}
	});
	
	$("#report_btn").click(function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $("#info_name").text();
		if (confirm("Are you sure to report " + id + " for spam?")) {
			updateSentTip("Reporting " + id + " for spam...", 5000, "ing");
			$.ajax({
				url: "ajax/relation.php",
				type: "POST",
				data: "action=report&id=" + id,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						updateSentTip("You have reported " + id + " for spam!", 3000, "success");
					} else {
						updateSentTip("Failed to report " + id + " for spam, please try again.", 3000, "failure");
					}
				},
				error: function(msg) {
					updateSentTip("Failed to report " + id + " for spam, please try again.", 3000, "failure");
				}
			});
		}
	});
});

function onHide(){
	$this = $("#info_hide_btn");
	$this.after('<a class="btn" id="info_show_btn" href="javascript:void(0)">Show @</a>');
	$this.remove();

	$("#info_show_btn").click(function(){
		$(".timeline li").each(function(i,o) {
			$(this).show();
		});
		$(this).after('<a class="btn" id="info_hide_btn" href="javascript:void(0)">Hide @</a>');
		$(this).remove();
		$("#info_hide_btn").click(function(){
			onHide();
		});
		$.cookie("infoShow","show");
	});

	$(".timeline li").each(function(i,o) {
		if ($(this).find(".status_word").text().indexOf("@") > -1) {
			$(this).hide();
		}
	});
	$.cookie("infoShow","hide");
}
