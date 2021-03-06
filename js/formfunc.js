FILTER_COOKIE = 'filKey';
FILTER_COUNTER = 'filCnt';
FILTER_KEY_OPT ={
	expires: 30
}
$(function (){
		$(document).on("click", "#media_preview > li > a", function(){
				var li = this.parentNode;
				li.parentNode.removeChild(li);
				leaveWord();
			});
		$("#photoBtn").click(function (){
				$("#photoArea").slideToggle(100);
			});
		$("#imageUploadSubmit").click(function (e){
				e.preventDefault();
				ImageUpload();
			});
		$("#filterBtn").click(function (){
				$("#filterArea").slideToggle(100);
			});
		$("#symbolBtn").click(function (){
				$("#symArea").toggle();
			});
		$("#symbols span").click(function (){
	      var obj = document.getElementById('textbox'); 
	      var str = $(this).text();
	      if(document.selection){  
	         obj.focus();  
	         var sel=document.selection.createRange();  
	         document.selection.empty();  
	         sel.text = str;  
	      }else{  
	         var prefix, main, suffix;  
	         prefix = obj.value.substring(0, obj.selectionStart);  
	         main = obj.value.substring(obj.selectionStart, obj.selectionEnd);  
	         suffix = obj.value.substring(obj.selectionEnd);  
	         obj.value = prefix + str + suffix;  
	      }
			$("#symArea").hide();
			leaveWord();
		});
		$("#restoreBtn").click(function (){
				$('#textbox').val($.cookie('recover'));
				updateSentTip("Your previous tweet has been restored!", 3e3, "success");
			});
		$("#autoBtn").click(function(){
				if ($("#autoBtn").hasClass("fa-pause")){
					clearInterval(UPDATE_INTERVAL);
					$("#autoBtn").removeClass("fa-pause").addClass("fa-play");
					updateSentTip("Auto refresh deactivated!", 3e3, "success");
				}else{
					setUpdateInterval();
					$("#autoBtn").removeClass("fa-play").addClass("fa-pause");
					updateSentTip("Auto refresh activated!", 3e3, "success");
					update();
				}
			});
		$("#refreshBtn").click(function (){
				update();
				updateSentTip("Retrieving new tweets...", 3e3, "ing");
			});
		$("#filterSubmit").click(function (e){
				e.preventDefault();
				if ($.trim($('#iptFilter').val()).length == 0){
					updateSentTip("Please enter at least one keyword!", 3e3, "failure");
					return false;
				}else{
					$.cookie(FILTER_COOKIE, null);
					$.cookie(FILTER_COUNTER, null);
					updateSentTip("New keyword: " + $.trim($('#iptFilter').val()) + " added!", 3e3, "success");
					filterEle();
				}
			});
		$("#filterReset").click(function (e){
				e.preventDefault();
				$.cookie(FILTER_COOKIE, null);
				$.cookie(FILTER_COUNTER, null);
				$('#iptFilter').val("");
				updateSentTip("Filtered tweets have been restored!", 5e3, "success");
				$('#statuses .filter').slideDown("fast");
			});
		$("#filterHide").on("click",function (e){
			e.preventDefault();
			if ($(this).data("show_mentions")=="yes"){
				$('#statuses .reply').slideDown("fast");
				$('#filterHide').val("Hide @");
				$(this).data("show_mentions","no");
			} else {
				$('#statuses .reply').slideUp("fast");
				$('#filterHide').val("Show @");
				$(this).data("show_mentions","yes");
			}
		});
		$("#clearBtn").click(function(e){
				e.preventDefault();
				if (confirm("This will sweep your timeline and remove excess tweets, are you sure?")){
					$("#statuses .timeline").each(function(){
							$(this).find("li:gt(19)").remove();
						});
				}
			});
	});

	function createObjectURL(file) {
		if (window.webkitURL) {
			return window.webkitURL.createObjectURL(file);
		}
		return window.URL.createObjectURL(file);
	}
	
	function revokeObjectURL(url) {
		if (window.webkitURL) {
			return window.webkitURL.revokeObjectURL(url);
		}
		return window.URL.revokeObjectURL(url);
	}
	
	function ImageUpload(){
		var fileEle = $("#imageFile")[0];
		if (fileEle.files.length == 0) {
			return;
		}
		updateSentTip("Uploading your image...", 10000, "ing");
		var img = document.createElement('img');
		img.src = createObjectURL(fileEle.files[0]);
		img.onload = function() {
			revokeObjectURL(img.src);
		};
		$.ajaxFileUpload({
				url: 'ajax/uploadImage.php?do=image',
				timeout: 60000,
				secureuri: false,
				fileElementId: 'imageFile',
				dataType: 'json',
				success: function (data, status){
					$mediaPreview = $("#media_preview");
					if (data.media_id != undefined && data.media_id != "error"){
						if ($mediaPreview.length > 0) {
							img.setAttribute("media_id", data.media_id);
							var li = document.createElement('li');
							var hyperlink = document.createElement('a');
							li.appendChild(hyperlink);
							hyperlink.title = "Remove";
							var span = document.createElement('span');
							span.className = "fa fa-times";
							hyperlink.appendChild(img);
							hyperlink.appendChild(span);
							$mediaPreview[0].appendChild(li);
						}
						leaveWord();
						updateSentTip("Your image has been uploaded!", 3e3, "success");
					}else{
						updateSentTip("Failed to upload, please try again.", 3e3, "failure");
					}
				},
				error: function (data, status, e){
					updateSentTip("Failed to upload, please try again.", 3e3, "failure");
				}
			})
		return false;
	}
	
function enableFilter(){
	if ($.cookie(FILTER_COOKIE) != null && $.cookie(FILTER_COOKIE) != ""){
		$('#iptFilter').val(recoverKeywords());
		$.cookie(FILTER_COUNTER, null);
		filterEle();
	}
}
function filterEle(){
	if ($.trim($('#iptFilter').val()).length == 0){
		return false;
	}else{
		var objs;
		var targets = [];
		var keywords = keywordRegexp();
		if (keywords === $.cookie(FILTER_COOKIE)){
			objs = $('#statuses .timeline li:not(.filter:hidden)').find('.status_word');
		}else{
			objs = $('#statuses .timeline li').find('.status_word');
		}
		var reg = new RegExp(keywords, "i");
		for (i = 0; i < objs.length; i++){
			if (reg.test($(objs[i]).text())){
				targets.push(objs[i]);
			}
		}
		if ($.cookie(FILTER_COUNTER) != null && $.cookie(FILTER_COUNTER) != ''){
			$.cookie(FILTER_COUNTER, targets.length + parseInt($.cookie(FILTER_COUNTER)));
		}else{
			$.cookie(FILTER_COUNTER, targets.length);
		}
		hideMatched(targets);
		$.cookie(FILTER_COOKIE, keywords);
		setCounter();
	}
}
function hideMatched(obj){
	$(obj).parent().parent().addClass("filter").hide();
}
function isMatch(txt, keywords){
	var reg = RegExp(keywords, "i");
	return reg.test(txt);
}
function keywordRegexp(){
	if ($.cookie(FILTER_COOKIE) === null){
		return setFilterCookie();
	}else{
		return $.cookie(FILTER_COOKIE);
	}
}
function recoverKeywords(){
	return $.cookie(FILTER_COOKIE).replace(/\|/g, ',');
}
function setFilterCookie(){
	var strs = $('#iptFilter').val().split(",");
	var keywords = '';
	for (i = 0; i < strs.length; i++){
		if (strs[i] == "") continue;
		keywords += strs[i] + "|";
	}
	keywords = keywords.substr(0, keywords.length - 1);
	return keywords;
}
var option ={ expire: 30 };
$(document).ready(function (){
		enableFilter();
});
