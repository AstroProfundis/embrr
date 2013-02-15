$(function(){
	$(".delete_btn").hide();
	$(".rank_list").find("li").live("mouseover", function(){
		$(this).find(".delete_btn").css("display", "inline-block");
	});
	
	$(".rank_list").find("li").live("mouseout", function(){
		$(this).find(".delete_btn").hide();
	});
	
	$(".list_delete_btn").click(function(e){
		e.preventDefault();
		var $this = $(this);  
		var list_id = $(".list_id").text();
		var member_name = $.trim($(this).parent().parent().find(".rank_screenname").text());
		member_name = member_name.replace("(","").replace(")","");
		var member_id = $.trim($(this).parent().parent().find("#rank_id").text());
		
		var confirm = window.confirm("Are you sure to delete " + member_name + "?");
		if (confirm) {
			updateSentTip("Deleting " + member_name + "...", 5000, "ing");
			$.ajax({
				url: "ajax/delete.php",
				type: "POST",
				data: "id=" + list_id + "&list_member=" + member_id,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						$this.parent().parent().parent().remove();
						updateSentTip(member_name + " has been deleted.", 3000, "success");
					} else {
						updateSentTip("Failed to delete " + member_name + ".", 3000, "failure");
					}
				},
				error: function(msg) {
					updateSentTip("Failed to delete " + member_name + ".", 3000, "failure");
				}
			});
		}
	});
});
