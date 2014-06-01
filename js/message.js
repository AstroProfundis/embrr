//message function
$(function () {
	formFunc();
	var temp = [];
	var auto = [];
	$("a.user_name").each(function(){
		var u = this.id;
		if (!(u in temp)) {
			temp[u] = true;
			auto.push(u);
		}
	});
	$("#sent_id").autocomplete(auto);
	$("#allMessage").click(function(e) {
		var $this = $(e.target);
		var matches = ($this.attr('class') || '').match(/\w+_btn/);
		var type = matches ? matches[0] : '';
		switch(type) {
			case 'msg_replie_btn':
				e.preventDefault();
				$("#sent_id").val($this.parent().parent().find(".status_word").find(".user_name").attr("id"));
				$("#textbox").focus();
				break;
			case 'msg_delete_btn':
				e.preventDefault();
				var message_id = $.trim($this.parent().parent().find(".status_id").text());
				var confirm = window.confirm("Are you sure to delete this message?");

				if (confirm) {
					updateSentTip("Deleting message...", 5000, "ing");
					$.ajax({
						url: "ajax/delete.php",
						type: "POST",
						data: "message_id=" + message_id,
						success: function(msg) {
							if (msg.indexOf("success") >= 0) {
								$this.parent().parent().parent().remove();
								updateSentTip("Message deleted.", 3000, "success");
							} else {
								updateSentTip("Failed to delete this message!", 3000, "failure");
							}
						},
						error: function(msg) {
							updateSentTip("Failed to delete this message!", 3000, "failure");
						}
					});
				}
				break;
		}
	});
});
