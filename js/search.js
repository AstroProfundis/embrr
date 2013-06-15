$(function(){
	var theAC = null;
	var searches = null;
	$.ajax({
		url: "ajax/savedSearches.php",
		data: {method: "list"},
		type: "GET",
		success: function(msg) {
			searches = eval("("+msg+")");
			$(".ss_delete_btn").live("click",function(e){
				e.preventDefault();
				$("#query").val("");
				if (theAC != null && searches != null) {
					var selectedId = $(this).attr("id").substr(3);
					var selectedIndex = -1;
					$.each(searches, function(ind, ele){
						if (ele[0] == selectedId)
							selectedIndex = ind;
					});
					if (selectedIndex != -1) {
						var cfm = window.confirm("Are you sure to delete the saved search \"" + searches[selectedIndex][1] + "\"?");
						if (!cfm) return;
						$.ajax({
							url: "ajax/savedSearches.php",
							data: {method: "delete", ssid: selectedId},
							type: "GET",
							success: function(m) {
								if (m.indexOf("success") >= 0) {
									updateSentTip("Successfully deleted saved search!", 3000, "success");
									searches.splice(selectedIndex, 1);
									theAC.setOptions({data:searches});
								}
								else
									updateSentTip("Error in deleting saved search!", 3000, "failure");
							},
							error: function(m) {
								updateSentTip("Error in deleting saved search!", 3000, "failure");
							}
						});
					}
				}
			});
			theAC = $("#query").autocomplete(searches, {
				minChars:0,
				formatItem:function(data, i, total) {
					return "<a class=\"ss_delete_btn\" href=\"#\" id=\"sgt" + data[0] + "\" >delete</a>" + data[1];
				},
				formatMatch:function(data, i, total) {
					return data[1];
				},
				formatResult:function(data) {
					return data[1];
				}
			});
		},
		error: function(msg) {
			updateSentTip("Failed to fetch the saved searches!", 3000, "failure");
		}
	});

	formHTML = "<h2>What are you doing?</h2>" + formHTML + "<div class=\"clear\"></div>";
	$("#allTimeline").click(function(e) {
		var $this = $(e.target);
		var type = $this.attr('class');
		switch(type) {
			case 'rt_btn':
				e.preventDefault();
				if ($("#textbox").length > 0) {
					onRT($this);
				} else {
					$("#search_form").after(formHTML);
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
				if ($("#textbox").length > 0) {
					onReplie($this,e);
				} else {
					$("#search_form").after(formHTML);
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
			case 'delete_btn':
				e.preventDefault();
				onDelete($this);
				break;
			case 'rt_undo':
				e.preventDefault();
				onUndoRt($this);
				break;
		}
	});
	$("#submit_btn").click(function(e){
		updateStatus();
		e.preventDefault();
	});
	updateTrends();
});
