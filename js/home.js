// Global Const
var INTERVAL_COOKIE = 'homeInterval';
$(function () {
	formFunc();
	$("#allTimeline").click(function(e) {
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
				e.preventDefault();
				onUndoRt($this);
				break;
		}
	});
	markReply($("#allTimeline > li"));
	$("#submit_btn").click(function (e) {
		e.preventDefault();
		updateStatus();
	});
	$("body").live("click", function (e) {
		document.title = document.title.replace(/(\([0-9]+\))/g, "");
		$(".new").remove();
	});
	setUpdateInterval();
});
var setUpdateInterval = function () {
	if (!location.href.split("?")[1] || location.href.split("?")[1] == "p=1") {
		var interval = parseFloat($.cookie(INTERVAL_COOKIE));
		if (interval === 0.0) {
			return false;
		}
		interval = interval > 0 ? interval : 1;
		UPDATE_INTERVAL = setInterval(function () {
			update();
		}, interval * 1000 * 60);
	}
};
function update() {
	if (PAUSE_UPDATE === true) {
		window.setTimeout(update, 5000);
	} else if (PAUSE_TIMELINE === true) {
		return 0;
	} else {
		PAUSE_TIMELINE = true;
		updateSentTip('Retrieving new tweets...', 5000, 'ing');
		if ($.cookie("intervalChanged") === "true") {
			clearInterval(UPDATE_INTERVAL);
			$.cookie("intervalChanged", "")
			setUpdateInterval();
		}
		$("ol.timeline li.mine").removeClass("mine").addClass("myTweet");
		var since_id = $("ol.timeline li:not(.myTweet):not(#ajax_statuses li):first").find(".status_id").text();
		$.ajax({
			url: "ajax/updateTimeline.php",
			type: "GET",
			dataType: "text",
			data: "since_id=" + since_id,
			success: function (msg) {
				if ($.trim(msg).indexOf("</li>") > 0) {
					var source = $(msg).prependTo($(".timeline"));
					var num = 0;
					if (document.title.match(/\d+/) != null) {
						num = parseInt(document.title.match(/\d+/));
					}
					document.title = "(" + (num + $(msg).length - 1) + ") " + document.title.replace(/(\([0-9]+\))/g, "");
					markReply($('#allTimeline > li'));
					filterEle();
					embrTweet(source);
					if($("div.new").length == 1) {
						$("div.new").show().slideDown("fast");
					} else {
						$("div.new").filter(":first").remove();
						$("span.tweetcount").filter(":last").text(num + $(msg).length - 1);
					}
					$("span.big-retweet-icon").tipsy({
						gravity: 's'
					});
					previewMedia(source);
				} else {
					updateSentTip('No new tweets', 3000, 'failure');
				}
				PAUSE_TIMELINE = false;
			},
			error: function (msg) {
				PAUSE_TIMELINE = false;
			}
		}); 
	}
}
