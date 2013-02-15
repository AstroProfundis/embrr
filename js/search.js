$(function(){
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
