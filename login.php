<?php 
	include ('lib/twitese.php');
 
	delCookie('oauth_token');
	delCookie('oauth_token_secret');
	delCookie('user_id');
	delCookie('twitese_name');
	delCookie('twitese_pw');
	delCookie('friends_count');
	delCookie('statuses_count');
	delCookie('followers_count');
	delCookie('imgurl');
	delCookie('name');
	delCookie('listed_count');
	delCookie('recover');
	delCookie('homeInterval');
	delCookie('updatesInterval');
	session_destroy();
	
	ob_start();
	if(!isset($_SESSION)){
		session_start();
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
<meta name="keywords" content="embr, open source, php, twitter, oauth" />
<meta name="description" content="Vivid Interface for Twitter" />
<meta name="author" content="Contributors" />
<title>Embr / Sign In</title>
<style>
*{list-style:none outside none;margin:0;padding:0;}
body{background:none repeat scroll 0 0 #F6F7F8;font:10px helvetica,arial,sans-serif;text-align:center;margin:0;padding:0;}
a:active,a:focus{outline:none;}
a{color:#BFBFBF;outline:medium none;text-decoration:none;}
.form-container #container{background:none repeat scroll 0 0 transparent;margin-top:70px;text-align:left;width:400px;border-width:0;}
.form-container #header{position:absolute;text-align:center;z-index:-1;}
.form-container #header h1{margin-left:-50px;margin-top:-30px;}
.form-container .form{box-shadow:0 0 20px #666;-webkit-box-shadow:0 0 20px #666;-moz-box-shadow:0 0 20px #666;background-color:#F1F8FC;margin-top:66px;opacity:0.8;}
.form-container #footer{text-align:center;}
#container{background:none repeat scroll 0 0 #FFF;border:1px solid #D6D8D9;text-align:left;width:920px;margin:0 auto;padding:20px;}
#header h1{float:none;}
#footer{color:#BFBFBF;font-size:13px;text-align:center;padding:10px;}
.button:hover{background-position:center center;}
.button:active{background-position:center bottom;}
.rounded_left_12px{-moz-border-radius-topleft:12px;-moz-border-radius-bottomleft:12px;border-top-left-radius:12px;border-bottom-left-radius:12px;border-top-left-radius:12px;border-bottom-left-radius:12px;}
.rounded_right_12px{-moz-border-radius-topright:12px;-moz-border-radius-bottomright:12px;border-top-right-radius:12px;border-bottom-right-radius:12px;border-top-right-radius:12px;border-bottom-right-radius:12px;}
.rounded_5px{-moz-border-radius:5px;border-radius:5px;border-radius:5px;}
.clear{display:block;}
.form{padding:0 2px;}
.form > fieldset{border-width:0;padding:20px 10px 10px;}
.form fieldset fieldset{border-width:0;padding:10px 0 0;}
.form .checkbox{color:#666;font-size:1.15em;margin-left:90px;}
.form .checkbox input{float:left;margin:0 .5em 0 0;}
.form .form-footer{background:none repeat scroll 0 0 #E0F0FB;clear:both;font-size:12px;height:25px;line-height:25px;margin:10px 0 0;padding:10px;}
.form .form-footer .delete:hover{color:#B42B2B;text-decoration:underline;}
.form-footer .button{background:none repeat scroll 0 0 #FFF;border:1px solid #EEE;color:#666;cursor:pointer;display:inline-block;margin-left:-2px;font-weight:700;text-align:center;font-family:helvetica;outline:0;zoom:1;font-size:13px;height:21px;line-height:20px;vertical-align:middle;padding:1px 20px 2px;}
.form-footer .button:hover{background-color:#DDD;}
.form-footer .button:active{background:none repeat scroll 0 0 #999!important;color:#CCC!important;outline:medium none;text-shadow:0 -1px 0 #404348;border-color:#61676F!important;}
.form-footer .button.cancel:hover{background-color:#EDEDED;}
.form-footer input.button{outline:none!important;font-size:13px;height:24px;padding-top:0;vertical-align:middle;}
.form-footer .button::-moz-focus-inner{border-color:transparent!important;}
a:hover,.form-footer{color:#999;}
#oauth {border:none;line-height: 22px;margin-right:-2px;padding-left:25px;}
</style>
<script type="text/javascript">
function register() {
	if (window.confirm("Make sure you can get access to twitter.com!")) {
		window.open("https://mobile.twitter.com/signup", "registerwindow", "height=450, width=600, toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=yes, status=yes");
	}
}
</script>
</head>
 
<?php if(BASIC_AUTH) require ('basic_auth.php'); ?>
<body class="form-container">
<div class="clear rounded_5px" id="container">
 <div id="header">
<h1><a href="/"><img border="0" alt="Embr" height="167" width="500" src="img/big_logo.png"></a></h1>
</div>
 
<?php if(isset($_GET['oauth']) && $_GET['oauth'] == 'denied') {?> 
<style type="text/css">
.form-container .form {display:none}
</style>
<div style="display: block; color: rgb(255, 0, 0); margin-top: 150px; margin-bottom: 10px; text-align: center;">
<h1 style="font-size: 20px;">Sorry, you are unauthorized to this site!</h1>
<p style="font-size: 12px;">Please contact the sitemaster for the ID-authorization issues.</p>
</div>
<?php } ?>
 <!--[if lt IE 9]>
<style type="text/css">
.form-container .form {display:none}
</style>
<div style="display: block; color: rgb(255, 0, 0); margin-top: 150px; margin-bottom: 10px; text-align: center;">
<h1 style="font-size: 20px;">Sorry, higer version of IE is needed!</h1>
<p style="font-size: 12px;">For a better experience using this site, please update your IE or use web browsers with WebKit or Gecko core, like Firefox Chrome and Safari.</p>
</div>
  <![endif]-->
<form class="form rounded_5px" id="form_login" method="post" action="oauth.php">
<fieldset class="clear">
		<div class="form-footer rounded_5px" style="padding-left:100px">
			<input type="submit" class="button rounded_left_12px" id="oauth" name="signin" value="Sign In">
<a class="button rounded_right_12px" id="register" title="register" onclick="register()">Sign Up!</a>
</div>
<ol>
<li>
<fieldset>
<ul>
<li class="checkbox"><input type="hidden" name="remember" value="0"><input type="checkbox" checked="checked" id="agent_session_remember_me" name="proxify" value="1">I CANNOT access to twitter.com!</li>
</ul>
</fieldset>
</li>
</ol>
</fieldset>
</form>
<div style="background: none repeat scroll 0% 0% transparent;" id="footer">
<p>&copy; 2011 Contributors <?php if(isset($_COOKIE['twitese_name'])) echo 'and '.$_COOKIE['twitese_name'];?> &middot; <a href="http://code.google.com/p/embr/" target="_blank" title="Embr Open Source">Open Source</a></p>
</div>
</div>
</body>
</html>
<?php ob_end_flush(); ?>