$(function(){
	$(".rt_btn").live("click", function(e){
		e.preventDefault();
		onRT($(this));
	});
	
	$(".replie_btn").live("click", function(e){
		e.preventDefault();
		onReplie($(this),e);
	});
	$(".favor_btn").live("click", function(e){
		e.preventDefault();
		onFavor($(this));
	});
	$(".unfollow_list").live("click",function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $this.parent().parent().find(".rank_name").text().substr(1);
		updateSentTip("Unfollowing lists...", 5000, "ing");
		$.ajax({
			url: "ajax/list.php",
			type: "POST",
			data: "action=destory&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					updateSentTip("Successfully unfollowing list" + id, 3000, "success");
					$this.parent().parent().parent().fadeOut("fast");
				} else {
					updateSentTip("Unfollow failed. Please try again.", 3000, "failure");
				}
			},
			error: function(msg) {
				updateSentTip("Unfollow failed. Please try again.", 3000, "failure");
			}
		});
		
	});

	$(".delete_list").click(function(e){
		e.preventDefault();
		var $this = $(this);  
		var list_id = $this.parent().parent().find(".rank_name").text().substr(1);
		var confirm = window.confirm("Do you really want to delete " + list_id + "?");
		if (confirm) {
			updateSentTip("deleting list" + list_id + "...", 5000, "ing");
			$.ajax({
				url: "ajax/delete.php",
				type: "POST",
				data: "list_id=" + list_id,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						$this.parent().parent().parent().fadeOut("fast");
						updateSentTip("Successfully deleting" + list_id, 3000, "success");
					} else {
						updateSentTip("Delete failed. Please try again.", 3000, "failure");
					}
				},
				error: function(msg) {
					updateSentTip("Delete failed. Please try again.", 3000, "failure");
				}
			});
		}
	});
	
	$("#list_create_btn").click(function(e){
		e.preventDefault();
		$("#list_form").toggle("fast");
		$("#list_name").focus().val("");
		$("#list_description").val("");
		$("#list_protect").removeAttr("checked");
		$("#pre_list_name").val("");
		$("#is_edit").val(0);
		$("#list_submit").val("Create");
	});
	
	$(".edit_list").click(function(e){
		e.preventDefault();
		var parent = $(this).parent().parent();
		var list_name = parent.find(".rank_name").text().split("/")[1];
		var list_description = parent.find(".rank_description").text().slice(3);
		var list_protect = parent.find(".rank_count").text().indexOf("Private") > 0;

		$("#list_form").show("fast");
		$("#list_name").focus().val(list_name);
		$("#list_description").val(list_description);
		if (list_protect) { 
			$("#list_protect").attr("checked", "checked");
		} else {
			$("#list_protect").removeAttr("checked");
		}
		$("#is_edit").val(1);
		$("#list_submit").val("Edit");
		$("#pre_list_name").val(list_name);
	})
	
	
	
	$(".add_member").click(function(e){
		e.preventDefault();
		$("#member_form").remove();
		var position = $(this).position();
		var liPosition = $(this).parent().parent().parent().position();
		var list_name = $(this).parent().parent().find(".rank_name").text().split("/")[1];
		var owner_name = $(this).parent().parent().find(".rank_name").text().split("/")[0];
		var rank_count = $(this).parent().parent().find(".rank_count");
		owner_name = owner_name.split("@")[1];
		$('<form method="POST" action="./lists.php?t=1" id="member_form">' +
	    	'<span>User ID:(Saperated with comma, e.g. JLHwung,twitter)</span>' +
	    	'<span><textarea type="text" name="list_members" id="list_members"></textarea></span>' +
	    	'<span><input type="submit" class="btn" id="member_submit" value="Submit" /> <input type="button" class="btn" id="member_cancel" value="Cancel" /></span>' +
	    '</form>').appendTo("#statuses").css("left", liPosition.left + position.left).css("top", liPosition.top + position.top + 30);
		
		$("#member_cancel").click(function(){
			$("#member_form").remove();
		});
		
		$("#member_submit").click(function(e){
			e.preventDefault();
			var list_members = $("#list_members").val();
			if (list_members.length <= 0) {
				window.alert("User IDs cannot be empty!");
				return;
			}
			$("#member_form").remove();
			updateSentTip("adding members to list " + list_name + "...", 5000, "ing");
                        $.ajax({
                                url: "ajax/addMembersToList.php",
                                type: "POST",
                                data: "slug=" + list_name + "&owner=" + owner_name + "&add_members=" + list_members,
                                success: function(msg) {
                                        if (msg.indexOf("error") >= 0) {
                                                updateSentTip("Adding failed. Please try again.", 3000, "failure");
                                        } else {
                                                updateSentTip("Successfully adding members to list " + list_name, 3000, "success");
						rank_count.html(msg);
                                        }
                                },
                                error: function(msg) {
                                        updateSentTip("Adding failed. Please try again.", 3000, "failure");
                                }
                        });
		})
	})
	
});
