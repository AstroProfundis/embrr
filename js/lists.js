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
					updateSentTip("Successfully unfollowing list " + id, 3000, "success");
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

	$(".delete_list").live("click", function(e){
		e.preventDefault();
		var $this = $(this);  
		var list_slug = $this.parent().parent().find(".rank_name").text().split("/")[1];
		var confirm = window.confirm("Do you really want to delete " + list_slug + "?");
		if (confirm) {
			updateSentTip("deleting list " + list_slug + "...", 5000, "ing");
			$.ajax({
				url: "ajax/delete.php",
				type: "POST",
				data: "list_slug=" + list_slug,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						updateSentTip("Successfully deleting " + list_slug, 3000, "success");
						$this.parent().parent().parent().fadeOut("fast");
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
	
	$("#list_create_btn").live("click", function(e){
		e.preventDefault();
		$("#list_form").toggle("fast");
		$("#list_name").focus().val("");
		$("#list_description").val("");
		$("#list_protect").removeAttr("checked");
		$("#pre_list_name").val("");
		$("#list_spanid").val("");
		$("#is_edit").val(0);
		$("#list_submit").val("Create");
	});
	
	$(".edit_list").live("click", function(e){
		e.preventDefault();
		var parent = $(this).parent().parent();
		var list_name = parent.find(".rank_name").text().split("/")[1];
		var list_description = parent.find(".rank_description").text().slice(5);
		var list_protect = parent.find(".rank_count").text().indexOf("Private") > 0;

		$("#list_form").show("fast");
		$("#list_name").focus().val(list_name);
		$("#list_description").val(list_description);
		if (list_protect) { 
			$("#list_protect").attr("checked", "checked");
		} else {
			$("#list_protect").removeAttr("checked");
		}
		$("#list_spanid").val(parent.attr("id"));
		$("#is_edit").val(1);
		$("#list_submit").val("Edit");
		$("#pre_list_name").val(list_name);
	})
	
	$("#list_submit").live("click", function(e){
		e.preventDefault();
		var list_name = $("#list_name").val();
		if (list_name.length == 0) {
			window.alert("List name cannot be empty!");
			return;
		}
		$('#list_form').slideToggle(300);
		var list_description = $("#list_description").val();
		var list_protect = $("#list_protect").attr("checked") == "checked" ? "private" : "public";
		var pre_list_name = $("#pre_list_name").val();
		var is_edit = $("#is_edit").val();
		var postdata = {"name" : list_name,
				"description" : list_description,
				"mode" : list_protect};
		if (is_edit == 1) {
			postdata["slug"] = pre_list_name;
			updateSentTip("editing list " + pre_list_name + "...", 5000, "ing");
			var spanid = $("#list_spanid").val();
		} else
			updateSentTip("creating list " + list_name + "...", 5000, "ing");
			
		$.ajax({
			url: "ajax/modifyList.php",
			type: "POST",
			dataType: "json",
			data: postdata,
			success: function(msg) {
				if (msg.result == 'success') {
					if (is_edit == 1) {
						updateSentTip("Successfully modifying list " + pre_list_name, 3000, "success");
						var rank_content = $("#"+spanid);
						rank_content.find(".rank_name").html('<a href="list.php?id='+msg.listuri+'"><em>'+msg.username+'/</em>'+list_name+'</a>');
						var rank_count = rank_content.find(".rank_count");
						rank_count.html(rank_count.html().replace(/Public|Private/, list_protect == "public" ? "Public" : "Private"));
						rank_content.find(".rank_description").html("Bio: "+list_description);
					}
					else {
						updateSentTip("Successfully creating list " + list_name, 3000, "success");
						var html = '<li><span class=\"rank_img\"><img src="'+msg.imgurl+'" /></span>';
						html += '<div class="rank_content" id="'+msg.contentid+'"><span class="rank_num"><span class="rank_name">';
						html +='<a href="list.php?id='+msg.listuri+'"><em>'+msg.username+'/</em>'+list_name+'</a>';
						html += '</span></span><span class="rank_count">Followers: 0&nbsp;&nbsp;Members: 0&nbsp;&nbsp;'+(list_protect == "public" ? "Public" : "Private")+'</span>';
						html += '<span class="rank_description">Bio: '+list_description+'</span>';
						html += '<span id="list_action"><a id="btn" href="#" class="edit_list">Edit</a> <a id="btn" href="#" class="delete_list">Delete</a> <a id="btn" href="#" class="add_member">Add Members</a></span>';
						html += "</div></li>";
						$(html).prependTo($(".rank_list")).fadeIn('fast');
					}
				} else {
					if (is_edit == 1)
						updateSentTip("Editing failed. Please try again.", 3000, "failure");
					else
						updateSentTip("Creating failed. Please try again.", 3000, "failure");
				}
			}
		});
	});
	
	$(".add_member").live("click", function(e){
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
                                data: {	"slug" : list_name,
					"owner" : owner_name,
					"add_members" : list_members},
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
