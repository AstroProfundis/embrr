$(function(){
	$(".rt_btn").click(function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onRT($(this));
		} else {
			$("#info_head").after('<h2>What are you doing?</h2>' + formHTML);
			formFunc();
			onRT($(this));
		}
	});
	$(".retw_btn").live("click", function(e){
			e.preventDefault();
			onNwRT($(this));
	});
	$(".rt_undo").live("click", function(e){
		e.preventDefault();
		onUndoRt($(this));
	});
	$(".replie_btn").live("click", function(e){
		e.preventDefault();
		var replie_id = $(this).parent().parent().find(".status_word").find(".user_name").text();
		if ($("#textbox").length > 0) {
			onReplie($(this),e);
		} else {
			$("#info_head").after('<h2>In reply to ' + replie_id + '</h2>' + formHTML);
			formFunc();
			onReplie($(this),e);
		}
	});

	$("#list_send_btn").click(function(e){
		e.preventDefault();
		if ($("#textbox").length == 0) {
			$("#info_head").after('<h2>What are you doing?</h2>' + formHTML);
		formFunc();
		}
	});
	
	$(".favor_btn").live("click", function(e){
		e.preventDefault();
		onFavor($(this));
	});

	$("#list_follow_btn").live("click", function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $("#info_name").text();
		updateSentTip("Following list " + id + "...", 5000, "ing");
		
		$.ajax({
			url: "ajax/list.php",
			type: "POST",
			data: "action=create&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					updateSentTip("You have followed " + id + ".", 3000, "success");
					$this.after('<a class="info_btn_hover" id="list_block_btn" href="#">Unfollow</a>');
					$this.remove();
				} else {
					updateSentTip("Failed to follow list " + id + ".", 3000, "failure");
				}
			},
			error: function(msg) {
				updateSentTip("Failed to follow list " + id + ".", 3000, "failure");
			}
		});
	});
	
	
	$("#list_block_btn").live("click", function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $("#info_name").text();
		updateSentTip("Unfollowing list " + id + "...");
		$.ajax({
			url: "ajax/list.php",
			type: "POST",
			data: "action=destory&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					updateSentTip("You have unfollowed list " + id + ".", 3000, "success");
					$this.after('<a class="info_btn" id="list_follow_btn" href="javascript:void(0)">Unfollow</a>');
					$this.remove();
				} else {
					updateSentTip("Failed to unfollow list " + id + ".", 3000, "failure");
				}
			},
			error: function(msg) {
				updateSentTip("Failed to unfollow list " + id + ".", 3000, "failure");
			}
		});
		
	});
	
	document.onclick = function(){
		document.title =document.title.replace(/(\([0-9]+\))/g, "");
	}
	var args = location.href.split("?")[1];	
	if (!args.split("&")[1] || args.split("&")[1] == "p=1") {
		setInterval(function(){
				update();
		}, 2000*60);
	}
});

function update() {
	var since_id = $(".timeline li:first-child").find(".status_id").text();
	var list_id = $("#info_name").text();
	$.ajax({
		url: "ajax/updateList.php",
		type: "GET",
		dataType: "text",
		data: "id=" + list_id + "&since_id=" + since_id,
		success: function(msg) {
			
			if ($.trim(msg).indexOf("</li>") > 0) {
				$(".timeline").prepend(msg);
				var num = 0;
				if (document.title.match(/\d+/) != null) {
					num = parseInt(document.title.match(/\d+/));
				}
				document.title = "(" + (num+$(msg).length )+ ")" + document.title.replace(/(\([0-9]+\))/g, "");
			}
			
		}
	});
}