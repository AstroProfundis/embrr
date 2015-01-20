$(function(){
	formFunc();
	$(document).on("click", "ol.timeline", function(e) {
		var $this = $(e.target);
		var matches = ($this.attr('class') || '').match(/\w+_btn/);
		var type = matches ? matches[0] : '';
		switch(type) {
			case 'rt_btn':
				e.preventDefault();
				onRT($this);
				break;
			case 'retw_btn':
				e.preventDefault();
				onNwRT($this);
				break;
			case 'replie_btn':
				e.preventDefault();
				onReplie($this,e);
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
			case 'unrt_btn':
				e.preventDefault();
				onUndoRt($this);
				break;
		}
	});
});
