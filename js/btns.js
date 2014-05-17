$(function(){
		formFunc();
		$(document).on("click", "ol.timeline", function(e) {
			var $this = $(e.target);
			var type = $this.attr('class');
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
				case 'rt_undo':
				case 'unrt_btn':
					e.preventDefault();
					onUndoRt($this);
					break;
				case 'msg_replie_btn':
					e.preventDefault();
					onReplieDM($this);
					break;
				case 'msg_delete_btn':
					e.preventDefault();
					onDeleteMsg($this);
					break;
			}
		});
		$("#submit_btn").click(function(e){
				updateStatus();
				e.preventDefault();
			});
	});
