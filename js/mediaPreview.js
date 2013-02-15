// Flash preview
TUDOU_EMBED = '<br /><embed src="http://www.tudou.com/v/src_id" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="420" height="363"></embed>';
XIAMI_EMBED = '<br /><embed src="http://www.xiami.com/widget/0_src_id/singlePlayer.swf" type="application/x-shockwave-flash" width="257" height="33" wmode="transparent"></embed>';
YOUKU_EMBED = '<br /><embed src="http://player.youku.com/player.php/sid/src_id/v.swf" quality="high" width="420" height="363" align="middle" allowScriptAccess="allways" mode="transparent" type="application/x-shockwave-flash"></embed>';
YOUTUBE_EMBED = '<br /><embed src="http://www.youtube.com/e/src_id?enablejsapi=1&version=3&playerapiid=ytplayer" quality="high" width="420" height="363" align="middle" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true"></embed>';
KU6_EMBED='<br /><embed src="http://player.ku6.com/refer/src_id/v.swf" quality="high" width="420" height="363" align="middle" allowScriptAccess="allways" mode="transparent" type="application/x-shockwave-flash"></embed>';
VIMEO_EMBED='<br /><iframe src="http://player.vimeo.com/video/src_id" quality="high" width="420" height="363" align="middle" allowScriptAccess="allways" mode="transparent" type="application/x-shockwave-flash"></iframe>';
EMBED_FRAME = '';
function getFlashReg(sSite) {
	switch (sSite) {
	case 'www.xiami.com':
		EMBED_FRAME = XIAMI_EMBED;
		return /[\S]+\.xiami\.com\/song\/([\d]+)[\S]*/i;
		break;
	case 'www.tudou.com':
		EMBED_FRAME = TUDOU_EMBED;
		return /[\S]+.tudou.[\S]+\/([\w-]+)[\S]*/i;
		break;
	case 'v.youku.com':
		EMBED_FRAME = YOUKU_EMBED;
		return /[\S]+.youku.com\/v_show\/id_([\w-]+)[\S]*(.html)/i;
		break;
	case 'youtu.be':
		EMBED_FRAME = YOUTUBE_EMBED;
		return /youtu.be\/([\w-_?]+)[\S]*/i;
		break;
	case 'www.youtube.com':
		EMBED_FRAME = YOUTUBE_EMBED;
		return /[\S]+.youtube.[\S]+\/watch\?v=([\w-_?]+)[\S]*/i;
		break;
	case 'v.ku6.com':
		EMBED_FRAME = KU6_EMBED;
		return /[\S]+.ku6.com\/show\/([\w-]+)[\S]*(.html)/i;
	case 'vimeo.com':
		EMBED_FRAME = VIMEO_EMBED;
		return /vimeo.com\/(\d+)[\S]*/i;
	default:
		return null;
	}
}
var previewFlash = function (obj) {
	var reg = /http:\/\/([\w]*[\.]*[\w]+\.[\w]+)\//i;
	var embed = "";
	var href = obj.attr("href");
	if (reg.exec(href.toLowerCase()) !== null) {
		var re = getFlashReg(RegExp.$1);
		if (re !== null) {
			if (re.exec(href) !== null) {
				embed = EMBED_FRAME.replace(/src_id/, RegExp.$1);
				$(embed).appendTo(obj.parent().parent().find(".tweet"));
			}
		}
	}
}

function get_img_processor(type) {
	switch (type) {
	case "instagr.am":
		proc = {
			reg: /^http:\/\/(?:www\.)?instagr\.am\/([\w\/]+)/,
			func: function (url_key, url_elem) {
				var src = "http://instagr.am/" + url_key[1] + "media/?size=m";
				append_image(src, url_elem);
			}
		};
		return proc;
	case "twitgoo.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?twitgoo\.com\/(\w+)/,
			func: function (url_key, url_elem) {
				var src = "img.php?imgurl=http://twitgoo.com/show/thumb/" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "yfrog.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?yfrog\.com\/(\w+)/,
			func: function (url_key, url_elem) {
				var src = "img.php?imgurl=" + url_key[0] + ":iphone";
				append_image(src, url_elem);
			}
		};
		return proc;
	case "twitpic.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?twitpic\.com\/(\w+)/,
			func: function (url_key, url_elem) {
				var src = "img.php?imgurl=http://twitpic.com/show/large/" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "img.ly":
		proc = {
			reg: /^http:\/\/(?:www\.)?img\.ly\/(\w+)/,
			func: function (url_key, url_elem) {
				var src = "img.php?imgurl=http://img.ly/show/medium/" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "ow.ly":
		proc = {
			reg: /^http:\/\/(?:www\.)?ow\.ly\/i\/(\w+)/,
			func: function (url_key, url_elem) {
				var src = "img.php?imgurl=http://static.ow.ly/photos/thumb/" + url_key[1] + ".jpg";
				append_image(src, url_elem);
			}
		};
		return proc;
	case "tweetphoto.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?tweetphoto\.com\/(\w+)/,
			func: function (url_key, url_elem) {
				var src = "http://api.plixi.com/api/TPAPI.svc/imagefromurl?size=medium&url=" + url_key[0];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "plixi.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?plixi\.com\/p\/(\w+)/,
			func: function (url_key, url_elem) {
				var src = "http://api.plixi.com/api/tpapi.svc/imagefromurl?size=medium&url=http://plixi.com/p/" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "hellotxt.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?hellotxt.com\/i\/(\w+)/,
			func: function (url_key, url_elem) {
				var src = "http://hellotxt.com/image/" + url_key[1] + ".s.jpg"
				append_image(src, url_elem);
			}
		};
		return proc;
	case "moby.to":
		proc = {
			reg: /^(http:\/\/(?:www\.)?moby\.to\/(\w+))/,
			func: function (url_key, url_elem) {
				var src = "http://api.mobypicture.com?s=small&format=plain&k=OozRuDDauQlucrZ3&t=" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "p.twipple.jp":
		proc = {
			reg: /^http:\/\/(?:p\.)?twipple\.jp\/(\w+)/,
			func: function (url_key, url_elem) {
				var src = "img.php?url=http://p.twipple.jp/show/large/" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "picplz.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?picplz\.com\/(\w+)/,
			func: function(url_key, url_elem) {
				$.getJSON('http://api.picplz.com/api/v2/pic.json?pic_formats=400r&callback=?&shorturl_id='+url_key[1],function (data) {
					if (data.result == "ok") {
						var imgsrc = data.value.pics[0].pic_files["400r"].img_url;
						append_image(imgsrc, url_elem);
					}
				});
			}
		};
		return proc;
	case "flic.kr": 
		proc = {
			reg: /^http:\/\/(?:www\.)?flic\.kr\/p\/(\w+)/,
			func: function (url_key, url_elem) {
				function base58_decode(snipcode) {
					var alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
					var num = snipcode.length;
					var decoded = 0;
					var multi = 1;
					for (var i = (num - 1); i >= 0; i--) {
						decoded = decoded + multi * alphabet.indexOf(snipcode[i]);
						multi = multi * alphabet.length;
					}
					return decoded;
				}
				var id = base58_decode(url_key[1]);
				var apiKey = '4ef2fe2affcdd6e13218f5ddd0e2500d';
				var url = "http://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=" + apiKey + "&photo_id=" + id;
				$.getJSON(url + "&format=json&jsoncallback=?", function (data) {
					if (data.stat == "ok") {
						var imgsrc = "img.php?imgurl=http://farm" + data.photo.farm + ".static.flickr.com/" + data.photo.server + "/" + data.photo.id + "_" + data.photo.secret + "_m.jpg";
						append_image(imgsrc, url_elem);
					}
				});
			}
		};
		return proc;
	case "twitxr.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?twitxr.com\/[^ ]+\/updates\/(\d+)/,
			func: function (url_key, url_elem) {
				var src = 'http://twitxr.com/thumbnails/' + url_key[1].substr(-2, 2) + '/' + url_key[1] + '_th.jpg';
				append_image(src, url_elem);
			}
		};
		return proc;
	default:
		return null;
	}
}
var append_image = function(src, elem) {
	var img = $('<img />').attr("src", src);
	var link = $(elem).clone().empty().append(img);
	$(elem).parent().after($('<div id="thumb_pic" />').append(link));
}
var previewImg = function (obj) {
	var rel = obj.attr("href");
	/(https?\:\/\/[\S]*\.(jpg|png|gif))/.exec(obj.attr("href"));
	if(RegExp.$2.length == 3){
		append_image(RegExp.$1, obj);
		return;
	}
	/https?\:\/\/(?:www\.)?([\w-.]+)\/[\S]*/i.exec(obj.attr("href"));
	var img_processor = get_img_processor(RegExp.$1);
	if (img_processor === null) {
		return null;
	}
	if ((img_url_key = img_processor.reg.exec(obj.attr("href"))) != null) {
		obj.attr("alt", "image");
		img_processor.func(img_url_key, obj);
	}
}
var previewMedia = function (objs) {
	var temp =[];
	objs.find("span.tweet a[rel^=noref], span.tweet_url").each(function () {
		var t = $(this);
		var href = t.attr("href");
		if(!(href in temp) && !t.data("previewed")) {
			if ($.cookie('showpic') === 'true') previewImg(t);
			if ($.cookie('mediaPre') === 'true') previewFlash(t);
			temp[href]=true;
		}
		t.data("previewed",true);
	});
}
// Check if jQuery's loaded
function GM_wait() {
	if (typeof $ == 'undefined') {
		window.setTimeout(GM_wait, 100);
	}
	else {
		$(document).ready(function () {
			previewMedia($('.timeline'));
		});
	}
}
GM_wait();