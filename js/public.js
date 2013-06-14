var UPDATE_INTERVAL;
var PAUSE_UPDATE = false;
var PAUSE_TIMELINE = false;
//form function
function updateSentTip(message,duration,className){
	var sentTip = $("#sentTip");
	var bgColor = $("body").css("background-color");
	sentTip.html(message).removeClass().addClass(className)
		.css({
			"border-style": "solid",
			"border-width": "1px",
			"border-color": "transparent"
		}).slideDown("fast");
	setTimeout(function (){
			sentTip.slideUp('fast');
		},duration);
	return sentTip;
};
function leaveWord(num){
	if(!num){
		num = 140;
	}
	var leave = num-$("#textbox").val().length;
	var sent_id = $("#sent_id").val();
	var $tb = $("#tip b");
	if (sent_id){
		leave -= sent_id.length+3;
	}
	if (leave < 0){
		$tb.text(leave).css("color","#C00");
		$("#tweeting_button").addClass('btn-disabled');
	}else{
		$tb.text(leave);
		$("#tweeting_button").removeClass('btn-disabled');
		if (leave > 40){
			$tb.css("color","#CCC");
		}else if(leave > 20){
			$tb.css("color","#CAA");
		}else if(leave > 10){
			$tb.css("color","#C88");
		}else{
			$tb.css("color","#C44");
		}
	}
	if(leave === 140){
		$("#in_reply_to").val("");
		$("#tweeting_button").addClass('btn-disabled');
	}
}
var formHTML = '<span id="tip"><b>140</b></span><form action="index.php" method="post"><textarea name="status" id="textbox"></textarea><input type="hidden" id="in_reply_to" name="in_reply_to" value="0" /><div id="tweeting_controls"><a class="a-btn a-btn-m btn-disabled" id="tweeting_button" tabindex="2" href="#" title="Ctrl/⌘+Enter also works!"><span>Send</span></a></div></form>';

var embrTweet=function(objs){
	if(typeof objs === 'undefined'){
		var objs = $('#statuses .timeline .source a');
	}else{
		var objs = objs.find('.source a');
	}
	objs.each(function (){
		var $this = $(this);
		if (/embr/i.test($this.text())) $this.addClass('embr');
	});
	if($("span.date a").length > 0){
		$("span.date a,#latest_meta a,#full_meta a").timeago();
	}else{
		$("span.date,#latest_meta a,#full_meta a").timeago();
	}
}
var formFunc = function(){
	leaveWord();
	$("#textbox").keyup(function (e){
			leaveWord();
			$(e.target).unbind('keydown');
			if ((e.ctrlKey || e.metaKey) && e.which == 13){
				if (PAUSE_UPDATE !== true){
					updateStatus();
				}else{
					return 0;
				}
			}else{
				if($.inArray(e.which,[91,93,224,17]) > -1){
					$(e.target).keydown(function(e){
						if(e.which == 13){
							if (PAUSE_UPDATE !== true){
								updateStatus();
							}else{
								return 0;
							}
							e.stopPropagation();
						}
					});
				}
			}
		});
	$("#tweeting_button").click(function (e){
		e.preventDefault();
		if ($("#textbox").val().length >0 ){
			updateStatus();
		}
	});		
};
	var updateStatus = function(){
	PAUSE_UPDATE = true;
	var text = $("#textbox").val();
	var sent_id = $("#sent_id").val();
	if(sent_id){
		text = "D "+sent_id+' '+text; 
	}
	var wordsCount = text.length;
	if (wordsCount > 140){
		$.cookie('recover',text,{'expire': 30});
		if(window.confirm("Your tweet is longer than 140 words! truncated? (you can restore later using restore button.)")){
			text = text.substr(0,137)+'...' ;
		}
	}
	if (wordsCount == 0 || $.trim(text).length == 0){
		updateSentTip("Your cannot send an empty tweet!",3e3,"failure");
		return false;
	}else{
		$('#tip').addClass('loading').find('b').css('color','transparent');
		$.cookie('recover',text,{'expire': 30});
		$.ajax({
			url: "ajax/update.php",
			type: "POST",
			data:{
				"status": text,
				"in_reply_to": $("#in_reply_to").val()
			},
			success: function (msg){
				if ($.trim(msg).indexOf("</li>") > 0){
					$('#tip').removeClass('loading').find("b").text("140").show();
					if ( (text.substring(0,2)).toUpperCase() == "D "){ //exclude the DMs. the exam of user_name is omitted.
						updateSentTip("Your DM has been sent!",3e3,"success");
						$("#sent_id,#textbox").val("");
						leaveWord();
					}else{
						updateSentTip("Your status has been updated!",3e3,"success");
						$("#textbox").val("");
						leaveWord();
						if(typeof INTERVAL_COOKIE !== 'undefined'){
							var source = $(msg).prependTo($("#allTimeline"));
							source.hide().slideDown('fast');
							var statusid = $.trim($(msg).find('.status_id').text());
							var statusText = $.trim($(msg).find('.tweet').html());
							embrTweet(source);
							$(".mine").slideDown("fast");
							$("#full_status").fadeIn("fast");
							$("#currently .status-text").hide().text(limitation(text)).fadeIn("fast");
							$("#latest_meta").hide().html("<a target=\"_blank\" href=\"status.php?id="+statusid+"\">less than 5 seconds ago</a>").fadeIn("fast");
							$("#currently .full-text").hide().html(statusText);
							$("#full_meta").hide().html("<a target=\"_blank\" href=\"status.php?id="+statusid+"\">less than 5 seconds ago</a>");
							$("#full_meta a,.full-text a").click(function (e){e.stopPropagation();});
							previewMedia(source);
							freshProfile();
						}
					}
				}else{
					$('#tip').removeClass('loading');
					leaveWord();
					updateSentTip("Update failed. Please try again.",3e3,"failure");
					$('#tweeting_button').removeClass('btn-disabled');
				}
				PAUSE_UPDATE = false;
			},
			error: function (msg){
				$('#tip').removeClass('loading');
				leaveWord();
				updateSentTip("Update failed. Please try again.",3e3,"failure");
				$('#tweeting_button').removeClass('btn-disabled');
				PAUSE_UPDATE = false;
			}
		});
	}
};
function shortUrlDisplay(){
	var stringVar = $("#textbox").val();
	if (stringVar.length === 0){
		updateSentTip("There's no URL in your tweet to shorten!",3e3,"failure");
	}else{
		var str = '';
		var regexp = /http(s)?:\/\/([\w\-]+\.)+[\w\-]+(\/[\w\-\.\/?\%\!\&=\+\~\:\#\;\,]*)?/ig;
		var l_urls = '';
		str = stringVar.match(regexp);
		if (str !== null){
			unshorten = 0;
			for (idx = 0; idx < str.length; idx++){
				regexp2 = /(http:\/\/j.mp\/[\S]+)|(http:\/\/bit.ly\/[\S]+)|(http:\/\/goo.gl\/[\S]+)|(http:\/\/t.co\/[\S]+)/gi;
				if (!str[idx].match(regexp2)){
					l_urls += str[idx]+"|";
				}else{
					unshorten++;
				}
			}
			if (unshorten){
				updateSentTip(unshorten+" URL(s) are maintained!",3e3,"failure");
			}
			if (l_urls != ""){
				$('#tip').addClass('loading').find('b').css('color','transparent');
				$.post("ajax/shorturl.php",{
					long_urls: l_urls
					},function (data){
					getShortUrl(data);
				});
			}
		}
	}
}
function getShortUrl(res){
	var $textbox = $('#textbox');
	var url_arry,s_url,l_url,part;
	var err_cnt = 0;
	url_arry = res.split('^');
	for (i = 0; i < url_arry.length; i++){
		part = url_arry[i].split('|');
		if (part.length == 2){
			s_url = part[0];
			l_url = part[1];
		}
		if (s_url){
			$textbox.val($textbox.val().replace(l_url,s_url)+"");
			leaveWord();
			$('#tip').removeClass('loading');
			updateSentTip("Successfully shortened your URLs!",3e3,"success");
		}	else{
			err_cnt++;
		}
	}
	if (err_cnt > 0){
		updateSentTip("Failed to shorten URLs,please try again.",3e3,"failure");
	}
}

function shortenTweet(){
	var tweet = $.trim($("#textbox").val());
	if (tweet.length === 0){
		updateSentTip("There's nothing to shorten!",3e3,"failure");
	}else{
		$('#tip').addClass('loading');
		$.ajax({
			url: "ajax/shortenTweet.php",
			type: "POST",
			data: "text="+tweet,
			success: function(msg){
				if(msg !== 'error'){
					$("#textbox").val(msg);
					leaveWord();
					$('#tip').removeClass('loading');
					updateSentTip("Your tweet has been shortened!",5e3,"success");
				}else{
					updateSentTip("Failed to shorten your tweet.",5e3,"failure");
				}
			},
			error: function(msg){
				updateSentTip("Failed to shorten your tweet.",5e3,"failure");
			}
		});
	}
}
$(function (){
	$("#latest_status").toggle(
		function (){
			$("#currently .status-text,#latest_meta").css("display","none");
			$("#currently .full-text,#full_meta").css("display","inline");
		},function (){
			$("#currently .status-text,#latest_meta").css("display","inline");
			$("#currently .full-text,#full_meta").css("display","none");
		});
	$("#full_meta a,.full-text a").click(function (e){
		e.stopPropagation();
	});
	var $temp = $("#currently .status-text");
	$temp.text(limitation($temp.text()));
	$("#translateMy").live("click",function(){
		var orig = $("#textbox").val();
			ORIG_TEXT = orig;
		var lang = $.cookie('myLangs')
		if(lang === null){
			lang = 'en';
		}
		$('#tip').addClass('loading');
		translate(orig,'',lang,'transMyCallback');
	});
});
var limitation = function (text){
	if (text.length > 60){
		text = text.substr(0,60)+" ...";
	}
	return text;
};
function ajax_reply($this){
	var $that = $this.parent().parent().parent().parent();
	var thread = $that.find(".ajax_form");
	if (thread.length > 0){
		thread.slideToggle("fast");
	}else{
		$that.addClass("loading");
		$.ajax({
			url: $this.attr("href"),
			type: "GET",
			dataType: "text",
			success: function(msg){
				$that.removeClass("loading");
				if ($.trim(msg).indexOf("</li>") > 0){
					var source = $(msg).appendTo($that);
					embrTweet(source);
				}else{
					updateSentTip('Get thread failed.',5e3,'failure');
				}
			},
			error: function(msg){
				updateSentTip('Get thread failed.',5e3,'failure');
				$that.removeClass("loading");
			}
		});
	}
}
//tweet function

function rminit($this){
	var $that = $this.parent().parent().parent();
	var $rm = $that.find(".right_menu");
	if($rm.length > 0){
		$rm.fadeIn('fast');
	}else{
		var id = $that.find(".status_word").find(".user_name").text();
		$that.addClass("loading");
		$.ajax({
			url: 'ajax/relation.php',
			type: "POST",
			data: "action=show&id="+id,
			success: function(msg){
				var html = '<ul class="right_menu round"><li><a class="rm_mention" href="#"><i></i>Mention</a></li>';
				var r = parseInt(msg);
				switch(r){
					case 1:
					html += '<li><a class="rm_dm" href="#"><i></i>Message</a></li>';
					case 2:
					html += '<li><a class="rm_unfollow" href="#"><i></i>Unfollow</a></li><li><a class="ul_block" href="#"><i></i>Block</a></li>';
					break;
					case 3:
					html += '<li><a class="rm_dm" href="#"><i></i>Message</a></li>';
					case 9:
					html += '<li><a class="rm_follow" href="#"><i></i>Follow</a></li><li><a class="rm_block" href="#"><i></i>Block</a></li>';
					break;
					case 4:
					html += '<li><a class="rm_follow" href="#"><i></i>Follow</a></li><li><a class="rm_unblock" href="#"><i></i>UnBlock</a></li>';
					break;
				}
				html += '<li><a class="rm_spam" href="#"><i></i>Report Spam</a></li><li><a href="user.php?id='+id+'">View Full Profile</a></ul>';
				$this.parent().parent().after(html);
				$(html).fadeIn('fast');
				$that.removeClass();
			},
			error: function(){
				updateSentTip('Loading Avatar Menu Failed,Please Retry!',3e3,"failure");
				$that.removeClass();
			}
		});
	}
}

function rmmention($this,e){
	var replie_id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
	var in_reply_id = $this.parent().parent().parent().find(".status_id").text();
	var text = "@"+replie_id;
	var mode = "In reply to ";
	scroll(0,0);
	$("#textbox").focus().val($("#textbox").val()+text+' ');
	$("#in_reply_to").val(in_reply_id);
	$("#full_status,#latest_meta,#full_meta,#currently .full-text").hide();
	$("#currently .status-text").html(mode+text);
	leaveWord();
}
function rmdm($this,e){
	var replie_id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
	var text = "D "+replie_id;
	scroll(0,0);
	$("#textbox").focus().val($("#textbox").val()+text+' ');;
	$("#in_reply_to").val(e.target.parent().parent().parent().find(".status_id").text());
	$("#full_status,#latest_meta,#full_meta,#currently .full-text").hide();
	$("#currently .status-text").html("Reply direct message to @"+replie_id);
	leaveWord();
}
function rmfollow($this){
	var id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
	updateSentTip("Following "+id+"...",5e3,"ing");
	$.ajax({
		url: "ajax/relation.php",
		type: "POST",
		data: "action=create&id="+id,
		success: function (msg){
			if (msg.indexOf("success") >= 0){
				updateSentTip("You have followed "+id+"!",3e3,"success");
			}else{
				updateSentTip("Failed to follow "+id+",please try again.",3e3,"failure");
			}
		},
		error: function (msg){
			updateSentTip("Failed to follow "+id+",please try again.",3e3,"failure");
		}
	});
}
function rmunfollow($this){
	var id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
	if (confirm("Are you sure to unfollow "+id+" ?")){
		updateSentTip("Unfollowing "+id+"...",5e3,"ing");
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=destory&id="+id,
			success: function (msg){
				if (msg.indexOf("success") >= 0){
					updateSentTip("You have unfollowed "+id+"!",3e3,"success");
				}else{
					updateSentTip("Failed to unfollow "+id+",please try again.",3e3,"failure");
				}
			},
			error: function (msg){
				updateSentTip("Failed to unfollow "+id+",please try again.",3e3,"failure");
			}
		});
	}
}
function rmblock($this){
	var id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
	if (confirm("Are you sure to block "+id+" ?")){
		updateSentTip("Blocking "+id+"...",5e3,"ing");
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=block&id="+id,
			success: function (msg){
				if (msg.indexOf("success") >= 0){
					updateSentTip("You have blocked "+id+"!",3e3,"success");
				}else{
					updateSentTip("Failed to block "+id+",please try again.",3e3,"failure");
				}
			},
			error: function (msg){
				updateSentTip("Failed to block "+id+",please try again.",3e3,"failure");
			}
		});
	}
}
function rmunblock($this){
	var id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
	if (confirm("Are you sure to unblock "+id+" ?")){
		updateSentTip("Unblocking "+id+"...",5e3,"ing");
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=unblock&id="+id,
			success: function (msg){
				if (msg.indexOf("success") >= 0){
					updateSentTip("You have unblocked "+id+"!",3e3,"success");
				}else{
					updateSentTip("Failed to unblock "+id+",please try again.",3e3,"failure");
				}
			},
			error: function (msg){
				updateSentTip("Failed to unblock "+id+",please try again.",3e3,"failure");
			}
		});
	}
}
function rmspam($this){
	var id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
	if (confirm("Are you sure to report "+id+" ?")){
		updateSentTip("Reporting "+id+" as a spammer...",5e3,"ing");
		$.ajax({
			url: "ajax/reportSpam.php",
			type: "POST",
			data: "spammer="+id,
			success: function (msg){
				if (msg.indexOf("success") >= 0){
					updateSentTip("Successfully reported!",3e3,"success");
				}else{
					updateSentTip("Failed to report "+id+",please try again.",3e3,"failure");
				}
			},
			error: function (msg){
				updateSentTip("Failed to report "+id+",please try again.",3e3,"failure");
			}
		});
	}
}
//tweet actions
function onFavor($this){
	var status_id = $.trim($this.parent().parent().find(".status_id").text());
	updateSentTip("Adding this tweet to your favorites...",5e3,"ing");
	$.ajax({
		url: "ajax/addfavor.php",
		type: "POST",
		data: "status_id="+status_id,
		success: function (msg){
			if (msg.indexOf("success") >= 0){
				updateSentTip("Favorite added successfully.",3e3,"success");
				$this.parent().parent().parent().append('<i class="faved"></i>');
				$this.removeClass().addClass("unfav_btn").attr("title","UnFav").text("UnFav");
			}else{
				updateSentTip("Add failed. Please try again.",3e3,"failure");
			}
		},
		error: function (msg){
			updateSentTip("Add failed. Please try again.",3e3,"failure");
		}
	});
}
function onReplie($this,e){
	var $word = $this.parent().parent().find(".status_word");
	var replie_id = $word.find(".user_name").text();
	var in_reply_id = $this.parent().parent().find(".status_id").text();
	var text = "@"+replie_id;
	var start = text.length+1;
	var mode = "In reply to ";
	if (!e.ctrlKey && !e.metaKey){
		var temp=[];
		temp[text] = true;
		var self = '@'+$("#side_name").text();
		temp[self] = true;
		var mentionArray = [text];
		var mentions = $word.find('.tweet').find('a[href^="user.php"]');
		$.each(mentions,function (){
			var t = this.text;
			if (!(t in temp)){
				temp[t] = true;
				mentionArray.push(t);
			}
			text = mentionArray.join(' ');
		});
		if (mentionArray.length > 1){
			mode = "Reply to all: ";
		}
	}
	if (e.altKey){
		mode = "Non-conversational reply to ";
		in_reply_id = "";
	}
	scroll(0,0);
	var end = text.length;
	$("#textbox").focus().val($("#textbox").val()+text+' ').caret(start,end);
	$("#in_reply_to").val(in_reply_id);
	$("#full_status,#latest_meta,#full_meta,#currently .full-text,#latest_meta").hide();
	$("#currently .status-text").html(mode+text);
	leaveWord();
}
function onRT($this){
	var replie_id = $this.parent().parent().find(".status_word").find(".user_name").text();
	scroll(0,0);
	var status_word = $this.parent().parent().find(".status_word").clone();
	status_word.find('.tweet a[rel=noreferrer]').each(function(){
		$(this).text($(this).attr('href'));
	});
	$("#textbox").focus().val(" RT @"+replie_id+":"+status_word.text().replace(replie_id,"")).caret(0);
	$("#full_status,#latest_meta,#full_meta,#currently .full-text,#latest_meta").hide();
	$("#currently .status-text").html("Retweet @"+replie_id+"'s tweet with comment.");
	leaveWord();
}
function onReplieDM($this){
	var replie_id = $this.parent().parent().find(".status_word").find(".user_name").text();
	var text = "D "+replie_id;
	scroll(0,0);
	$("#textbox").focus().val($("#textbox").val()+text+' ');
	$("#full_status,#latest_meta,#full_meta,#currently .full-text,#latest_meta").hide();
	$("#currently .status-text").html("Reply direct message to @"+replie_id);
	leaveWord();
}
function onNwRT($this){
	if (confirm("Are you sure to retweet this?")){
		var statusBody = $this.parent().parent();
		var status_id = statusBody.find(".status_id").text();
		var div = "#"+statusBody.parent().parent().attr('id');
		var btnDiv = div+"Btn";
		updateSentTip("Retweeting tweet...",5e3,"ing");
		$.ajax({
			url: "ajax/retweet.php",
			type: "post",
			data: "status_id="+status_id,
			success: function (msg){
				if (msg.length >= 0){
					statusBody.parent().addClass("retweet");
					statusBody.find(".source").hide();
					statusBody.find(".status_info").append("<span class=\"rt_source\">Retweeted by you from <a rel=\"nofollow\" href=\"http://code.google.com/p/embr/\">embr</a></span>").fadeIn("fast");
					statusBody.find(".date").hide();
					statusBody.find(".status_info").append("<span class=\"rt_undo\" title=\"Your followers will no longer see the tweet as retweeted by you.\">&nbsp;<a href=\"#\">(Undo)</a><span class=\"rt_id\" style=\"display: none;\">"+msg+"</span></span>").fadeIn("fast");
					updateSentTip("This tweet has been retweeted!",3e3,"success");
					$(".rt_undo").tipsy({
							gravity: 's'
						});
				}else{
					if (msg === "duplicated"){
						updateSentTip("You have retweeted this tweet!",3e3,"failure");
					}else{
						updateSentTip("Failed to retweet!",3e3,"failure");
					}
				}
			},
			error: function (msg){
				updateSentTip("Retweet failed. Please try again.",3e3,"failure");
			}
		});
	}
}
function UnFavor($this){
	if (window.confirm("Are you sure to unfavor this tweet?")){
		var $that=$this.parent().parent();
		var status_id = $.trim($that.find(".status_id").text());
		$that.parent().css("background-color","#FF3300");
		updateSentTip("Unfavoring tweet...",5e3,"ing");
		$.ajax({
			url: "ajax/delete.php",
			type: "POST",
			data: "favor_id="+status_id,
			success: function (msg){
				if (msg.indexOf("success") >= 0){
					if (location.href.indexOf('favor.php')>0){
						$that.parent().fadeOut("fast");
					}else{
						$that.parent().find(".faved").fadeOut("fast");
						$this.removeClass().addClass("favor_btn").attr("title","Fav").text("Fav");
					}
					updateSentTip("This tweet has been unfavored!",3e3,"success");
				}else{
					updateSentTip("Unfavor failed. Please try again.",3e3,"failure");
				}
				$that.parent().css("background-color","");
			},
			error: function (msg){
				updateSentTip("Unfavor failed. Please try again.",3e3,"failure");
				$that.parent().css("background-color","");
			}
		});
	}
}
function onDelete($this){
	if (window.confirm("Are you sure to delete this tweet?")){
		var $this=$this.parent().parent();
		var status_id = $.trim($this.find(".status_id").text());
		$this.parent().css("background-color","#FF3300");
		updateSentTip("Deleting tweet...",5e3,"ing");
		$.ajax({
			url: "ajax/delete.php",
			type: "POST",
			data: "status_id="+status_id,
			success: function (msg){
				if (msg.indexOf("success") >= 0){
					$this.parent().fadeOut("fast");
					updateSentTip("Your tweet has been destroyed!",3e3,"success");
				}else{
					updateSentTip("Delete failed. Please try again.",3e3,"failure");
				}
				$this.parent().css("background-color","");
			},
			error: function (msg){
				updateSentTip("Delete failed. Please try again.",3e3,"failure");
				$this.parent().css("background-color","");
			}
		});
	}
}
function onUndoRt($this){
	if (window.confirm("Are you sure to undo this retweet?")){
		var status_id = $.trim($this.parent().find(".rt_id").text());
		var statusBody = $this.parent().parent().parent();
		statusBody.css("background-color","#FF3300");
		updateSentTip("Undoing retweet...",5e3,"ing");
		$.ajax({
			url: "ajax/delete.php",
			type: "POST",
			data: "status_id="+status_id,
			success: function (msg){
				if (msg.indexOf("success") >= 0){
					statusInfo = $this.parent().parent();
					if (statusInfo.find(".rt_source").size() === 1){
						statusInfo.find(".source").show().find(".date").show();
						statusInfo.find(".rt_source").remove()
						statusInfo.find(".rt_undo").remove();
						statusBody.removeClass("retweet");
					}else{
						statusBody.fadeOut("fast");
					}
					updateSentTip("Your retweet has been undo!",3e3,"success");
				}else{
					updateSentTip("Undo failed. Please try again.",3e3,"failure");
				}
				statusBody.css("background-color","");
			},
			error: function (msg){
				updateSentTip("Undo failed. Please try again.",3e3,"failure");
				statusBody.css("background-color","");
			}
		});
		
	}
}
function onDeleteMsg($this){
	if (window.confirm("Are you sure to delete this message?")){
		var $this=$this.parent().parent();
		var message_id = $.trim($this.find(".status_id").text());
		$this.parent().css("background-color","#FF3300");
		updateSentTip("Deleting message...",5e3,"ing");
		$.ajax({
			url: "ajax/delete.php",
			type: "POST",
			data: "message_id="+message_id,
			success: function (msg){
				if (msg.indexOf("success") >= 0){
					$this.parent().fadeOut("fast");
					updateSentTip("Message deleted.",3e3,"success");
				}else{
					updateSentTip("Failed to delete this message!",3e3,"failure");
				}
				$this.parent().css("background-color","");
			},
			error: function (msg){
				updateSentTip("Failed to delete this message!",3e3,"failure");
				$this.parent().css("background-color","");
			}
		});
	}
}
$(function (){
	$("#statuses .trans_close").live('click',function(e){
		e.preventDefault();
		$(e.target).parent().parent().parent().parent().find(".translated").remove();
	});
	$("#transRecover").click(function(e){
		$("#textbox").val(ORIG_TEXT);
		$(e.target).fadeOut('fast');
		});
	});
var translate = function(text,context,lang,callback){
	
	var a = "http://www.google.com/uds/Gtranslate";
	a += "?callback="+callback;
	a += "&context="+context;
	a += "&q="+encodeURIComponent(text);
	a += "&key=notsupplied&v=1.0&nocache=1240207680396&langpair=%7C";
	a += lang;
	$.getScript(a);
};
var transMyCallback = function(content,translation){
	if(translation.translatedText !== null){
		$('#tip').removeClass('loading');
		$("#transArea").hide();
		$("#textbox").val(translation.translatedText);
		$("#transRecover").fadeIn('fast');
	}
};
var transCallback = function(content,translation){
	if(translation.translatedText !== null){
		var lang = $.cookie('transLang')
		if(lang === null){
			lang = 'zh';
		}
		var langTxt = $.cookie('fullLang');
		if(langTxt === null){
			langTxt = $('#transArea select[name=langs] option[value='+lang+']').text();
		}
		var html = '<div class="translated"><a href="#" title="Hide Translation" class="trans_close">(Hide)</a><span class="trans_header"><strong>Translation <small>(from '+translation.detectedSourceLanguage;
		html += ' to '+langTxt+')</small> : </strong></span>';
		html += '<span class="trans_body">'+translation.translatedText+'</span></div>';
		var li,target;
		if(typeof INTERVAL_COOKIE !== 'undefined'){
			li = $("#statuses ol:visible li:has(.status_id)").filter(":contains("+content+")");
			target = li.find(".status_word").filter(":first");
		}else{
			li = $("#statuses li:has(.status_id)").filter(":contains("+content+")");
			target = li.find(".status_word").filter(":first");
		}
		$(html).appendTo(target);
		li.removeClass("loading");
	}
};

$(function (){
	$('body').click(function (){
		$('ul.right_menu').fadeOut('fast');
	});
	$('ol.timeline').click(function(e){
		var $this = $(e.target);
		switch(e.target.id){
				//avatar menu
			case 'avatar':
				e.preventDefault();
				rminit($this);
				e.stopPropagation();
			break;
		}
		switch(e.target.className){
				//ajax_reply
			case 'ajax_reply':
				e.preventDefault();
				ajax_reply($this);
			break;
			//avatar_menu_action
			case 'rm_mention':
				e.preventDefault();
				rmmention($this,e);
			break;
			case 'rm_dm':
				e.preventDefault();
				rmdm($this,e);
			break;
			case 'rm_follow':
				e.preventDefault();
				rmfollow($this);
			break;
			case 'rm_unfollow':
				e.preventDefault();
				rmunfollow($this);
			break;
			case 'rm_block':
				e.preventDefault();
				rmblock($this);
			break;
			case 'rm_unblock':
				e.preventDefault();
				rmunblock($this);
			break;
			case 'rm_spam':
				e.preventDefault();
				rmspam($this);
			break;
			//translate
			case 'trans_btn':
				e.preventDefault();
				var tBody = $this.parent().parent();
				if(tBody.find(".trans_body").length !== 0){
					return;
				}
				var id = $.trim(tBody.find('.status_id').text());
				var text = $.trim(tBody.find('.tweet').text());
				var lang = $.cookie('transLang');
				if(lang === null){
					lang = 'zh';
				}
				tBody.parent().addClass('loading');
				translate(text,id,lang,'transCallback');
			break;
			// unshorturl 
			case 'tweet_url':
				var tp = $this.text().split('/');
				var d = tp[0];
				if(d == 't.cn' || d == 'goo.gl' || d == 'bit.ly' || d == 'j.mp' || d == 'is.gd' || d == '163.fm') {
					e.preventDefault();
					updateSentTip('Unshorting the URL...',3e3,'ing');
					$.getJSON('http://api.longurl.org/v2/expand?url=' +encodeURIComponent($this.attr('href')) + '&format=json&callback=?', function(data) {
						if('long-url' in data) {
							var url = data['long-url'];
							if (url != $this.attr('href')) {
								var tmp = url.split("://");
								$this.text(tmp[1]);
								$this.attr('href',url);
								updateSentTip('Successfully unshort the URL!',3e3,'success');
								if ($.cookie('showpic') === 'true') previewImg($this);
								if ($.cookie('mediaPre') === 'true') previewFlash($this);
								$this.data('previewed',true);
							}
						} else {
							updateSentTip('Fail to unshort the URL! Please try again later!',3e3,'failure');
						}
					});
				}
			break;
		}
	});
	$('ol.timeline').on("dbclick", 'a.tweet_url', function(e){
		$this = $(e.target);	
		
	});
});

//sidebar function
var scroller = function(){
	var $sidebar = $("#side");
	var $window = $(window);
	var top = $sidebar.data("top");
	if ($window.scrollTop() > top){
		$sidebar.css({
			marginTop: '',
			top: 0,
			position: 'fixed'
		});
	}else{
		$sidebar.css({
			marginTop:'',
			top:'',
			position:'relative'
		});
	}
}
var sidebarscroll = function (msg){
	if($.cookie('sidebarscroll') != 'false' && location.href.indexOf('profile.php')< 0 && location.href.indexOf('setting.php') < 0){
		var $sidebar = $("#side");
		var $window = $(window);
		if(!$sidebar.data("top")){
			var offset = $sidebar.offset();
			$sidebar.data("top",offset.top);
		}
		if(msg == undefined){
			$window.scroll(scroller);
		}
		if (msg == 'pause'){
			var top = $window.scrollTop() - $sidebar.data('top');
			if (top <= 0) top = 0;
			$sidebar.css({
				marginTop:top,
				position:'relative'
			});
			$window.unbind('scroll',scroller);
		}	
	}
};
$(function (){
	if($.cookie('autoscroll') != 'false' && $("#more").length > 0 && $("ol.timeline").length > 0){
		$("ol.timeline").infinitescroll({
			nextSelector:"#more:last",
			navSelector:"#pagination",
			itemSelector:"ol.timeline li",
			callback: function(obj){
				embrTweet(obj);
				previewMedia(obj);
			},
		});
	}
	$("#indicator").toggle(
		function (){
			$('#sidebarTip_more').slideDown('fast');
			$('#indicator').html('[-]');
		},function (){
			$('#sidebarTip_more').slideUp('fast');
			$('#indicator').html('[+]');
		});
	$("#sidebarTip [contenteditable]").live('focusout',function(){
		var $this = $("#sidebarTip [contenteditable]");
		$.post(
			'ajax/setTip.php',
			{Tip_Title: $this.eq(0).text()+' ',Tip_Content:$this.eq(1).text()+' ',Tip_More:$this.eq(2).text()+' '},
			function (msg){
				if(msg == 'unsecured'){
					updateSentTip('Fail to save your tip for the security issues!',3e3,'failure');
				}else if(msg == 'error'){
					updateSentTip('Fail to save your tip! Please try again later!',3e3,'failure');
				}else{
					updateSentTip('Successfully save your tip!',3e3,'success');
				}
			}
		);
	});
	$("#sidebarTip #tip_reset").live('click',function(e){
		e.preventDefault();
		if(window.confirm('Are you sure to restore to default tips?')){
			$.post(
				'ajax/setTip.php',
				{reset: 'true'},
				function (msg){
					if(msg == 'reset'){
						updateSentTip('Successfully restore to the default tips!',3e3,'success');
						location.reload();
					}else{
						updateSentTip('Fail to save your tip! Please try again later!',3e3,'failure');
					}
				}
			);
		}
	})
	$("#profileRefresh").click(function(e){
		e.preventDefault();
		var that = $(this);
		if (!that.hasClass('refreshing')){
			that.addClass('refreshing');
			$.ajax({
				url: "ajax/updateProfile.php",
				type: "GET",
				dataType: "json",
				success: function(msg){
					if (msg.result == 'success'){ 
						freshProfile();
						updateSentTip("Profile updated successfully!",3e3,"success");
					}
					else{
						updateSentTip("Failed to update your profile!",3e3,"failure");
					}
				},
				error: function (msg){
					updateSentTip("Failed to update your profile!",3e3,"failure");
				},
				complete: function(){
					that.removeClass('refreshing');
				}
			});
		}
	});
});

// sidepost function
$(function (){
	$("#trends_title").toggle(
		function (){
			$("#trends_title").removeClass().addClass("loading");
			updateTrends();
		},function (){
			$("#trends_title").removeClass();
			$("#trend_entries").slideUp("fast");
			sidebarscroll();
		});
	$("#following_title").toggle(
		function (){
			$("#following_title").removeClass().addClass("loading");
			sidebarscroll('pause');
			updateFollowing();
		},function (){
			$("#following_title").removeClass();
			$("#following_list").slideUp("fast");
			sidebarscroll();
		});
	$("#apiquota_title").toggle(
		function (){
			$("#apiquota_title").removeClass().addClass("loading");
			sidebarscroll('pause');
			updateAPIQuota();
		},function (){
			$("#apiquota_title").removeClass();
			$("#apiquota_list").slideUp("fast");
			sidebarscroll();
		});
});
function updateTrends(){
	sidebarscroll('pause');
	if (navigator.geolocation) {
		if ($.cookie('woeid') == undefined) {
			navigator.geolocation.getCurrentPosition(function (pos, error) {
				$.ajax({
					url: "ajax/updateTrends.php?lat=" + pos.coords.latitude + "&long=" + pos.coords.longitude,
					type: "GET",
					success: function (msg){
						if ($.trim(msg).indexOf("</ul>" > 0)){
							$("#trend_entries").html(msg);
						}
						$("#trends_title").removeClass().addClass("open");
						$("#trend_entries").slideDown("fast");
					}
				})
			});
		} else {
			$.ajax({
				url: "ajax/updateTrends.php",
				type: "GET",
				success: function (msg){
					if ($.trim(msg).indexOf("</ul>" > 0)){
						$("#trend_entries").html(msg);
					}
					$("#trends_title").removeClass().addClass("open");
					$("#trend_entries").slideDown("fast");
				}
			});
		}
	}
}
function updateFollowing(){
	$.ajax({
		url: "ajax/updateFollowing.php",
		type: "GET",
		success: function (msg){
			if ($.trim(msg).indexOf("</span>" > 0)){
				$("#following_list").html(msg);
			}
			$("#following_title").removeClass().addClass("open");
			$("#following_list").slideDown("fast");
		}
	});
}
function updateAPIQuota(){
	$.ajax({
		url: "ajax/apiQuota.php",
		type: "GET",
		success: function (msg){
			if ($.trim(msg).indexOf("</span>" > 0)){
				$("#apiquota_list").html(msg);
			}
			$("#apiquota_title").removeClass().addClass("open");
			$("#apiquota_list").slideDown("fast");
		}
	});
}
$(window).load(function(){
	var scrollTo = function (top,duration,callback){
	var w = $(window);
	var FPS = 50;
	var currentTop = w.scrollTop();
	var offset = (currentTop - top) / (duration * FPS / 1000);
	var n = 0;
	var prevTop = currentTop;
	var t = setInterval(function (){
			if ((prevTop - top) * (currentTop - top) <= 0){
				clearInterval(t);
				currentTop = prevTop = top;
				w.scrollTop(top);
				if (callback) callback();
			}else{
				prevTop = currentTop;
				w.scrollTop(currentTop -= offset);
			}
		},1e3 / FPS);
	}
	var scrollToTop = function(){
		scrollTo(0,200,function (){
				scrollTo(30,50,function (){
						scrollTo(0,50);
					});
			});
	};
	var scrollToBottom = function(){
		var height = document.body.clientHeight;
		scrollTo(height,200,function (){
			scrollTo(height+30,50,function (){
					scrollTo(height,50);
				});
		});
	};
	$('body').dblclick(function (){
		scrollToTop();
		$("#textbox").focus();
	});
	$('#content').dblclick(function (e){
		e.stopPropagation();
	});
	var hkFadeIn = function(text){
		$("#shortcutTip").fadeIn("fast").html(text);
	};
	var hkFadeOut = function(){
		setTimeout(function (){$("#shortcutTip").fadeOut("fast");},2000);
	};
	// hotkeys
	var hotkeyHandler = function(code){
		switch(code){
		case 82: // R - refresh
			hkFadeIn("Refresh");
			update();
			hkFadeOut();
			break;
		case 67: // C - focus textbox
		case 85: // U
			hkFadeIn("Compose");
			scrollTo(0,1,function (){
					$("#textbox").focus();
				});
			hkFadeOut();
			break;
		case 66: // B - scroll to bottom
			hkFadeIn("Boom!");
			scrollToBottom();
			hkFadeOut();
			break;
		case 84: // T - scroll to top
			hkFadeIn("Whiz!");
			scrollToTop();
			hkFadeOut();
			break;
		case 83: // S - search
			hkFadeIn("Search");
			$("#sidepost").animate({backgroundColor: "#FF6347"},500,function(){
					$("#header_search_query").focus();
					$("#sidepost").animate({backgroundColor: $("#side_base").css("background-color")},1000);
				});
			hkFadeOut();
			break;
		}
	};
	$(document).keydown(function(e){
		var tag = e.target.tagName;
		if(tag === "BODY" || tag === "HTML"){
			if(!e.altKey && !e.shiftKey && !e.ctrlKey && !e.metaKey){
				hotkeyHandler(e.keyCode);
			}
		}
	});
	$("#statuses .big-retweet-icon,#func_set .func_btn,#profileRefresh").tipsy({
		gravity: 's'
	});
	$('#symbols span').tipsy({
		gravity: $.fn.tipsy.autoNS
	});
	$("#statuses .mine").live("mouseout",function (e){
		$(e.target).removeClass("mine").addClass("myTweet");
	});
});
//init global functions
$(document).ready(function (){
	embrTweet();
	$("#primary_nav li a").click(function (e){
			$(e.target).each(function (){
					if ($(this).hasClass("active")){
						$(this).removeClass()
					}
				});
			$(this).removeClass().addClass("active").css("background","transparent url('../img/spinner.gif') no-repeat scroll 173px center")
		});
	$("#avatar,#sideimg").lazyload({threshold:100,effect:"fadeIn",placeholder:"img/blank.gif"});
	sidebarscroll();
});
var freshProfile = function(){
	$("#side_name").text($.cookie('name'));
	$.cookie('name',null);
	$("span.count").eq(0).text($.cookie('friends_count')).end()
		.eq(1).text($.cookie('followers_count')).end()
		.eq(2).text($.cookie('listed_count'));
	$("#update_count").text($.cookie('statuses_count'));
	$('#sideimg').attr("src",$.cookie('imgurl'));
};
var markReply = function(obj){
	var sidename = "@"+$("#side_name").text().toLowerCase();
	obj.each(function (i,o){
		if ($(this).find("> span").find('.tweet').text().toLowerCase().indexOf(sidename) > -1){
			$(this).addClass("reply");
		}
	});
};