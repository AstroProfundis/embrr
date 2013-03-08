<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('lib/twitese.php');
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Share to Embr</title>
<style>
body{background-color:#EEE;font-family:Tahoma,Helvetica,sans-serif;font-size:12px;margin:0}
h2{color:#666;display:block;float:left;font-family:Helvetica;font-weight:700;margin:8px 0 0;text-shadow:1px 1px #EEE}
p{margin:0;padding:0}
a:active, a:focus{outline:medium none}
a{color:#3280AB;text-decoration:none}
a:hover{color:#000;text-decoration:underline}
#tip{color:#999;float:right;font-size:12px}
#tip b{font-family:Tahoma,Helvetica,sans-serif;font-size:24px;margin:3px}
#share{border-radius:5px;box-shadow:0 0 5px #000;-moz-box-shadow:0 0 5px #000;-webkit-box-shadow:0 0 5px #000;background:url("../img/bg-front.gif") repeat-x scroll 0 0 transparent;height:230px;margin:20px 30px 0;padding:10px 20px;width:500px}
#textbox{border-radius:2px;background-color:#FBFBFB;border:1px solid #A7A6AA;font-family:'Lucida Grande',Tahoma,sans-serif;font-size:14px;height:80px;margin:0;overflow:hidden;padding:3px;width:490px}
#url{border-radius:2px;background-color:#FBFBFB;border:1px solid #A7A6AA;font-family:'Lucida Grande',Tahoma,sans-serif;font-size:12px;padding:3px;width:490px}
.title{display:block;width:40px}
table tr td{padding:5px 0}
#message{font-size:14px;margin-top:100px;text-align:center}
#textbox:hover, #url:hover{background-color:#FFF}
.more{background-color:#FFF;background-image:url("../img/more.gif");background-position:left top;background-repeat:repeat-x;border-color:#DDD #AAA #AAA #DDD;border-style:solid;border-width:1px;display:block;font-family:Helvetica;font-size:18px;font-weight:700;height:22px;letter-spacing:1px;line-height:2em;margin-bottom:6px;outline-style:none;outline-width:medium;padding:6px 0;width:100%}
.more:hover{background-position:left -78px;border:1px solid #bbb;text-decoration:none}
.more:active{background-position:left -38px;color:#666}
.more.loading{background-color:#fff;background-image:url(../img/ajax.gif);background-position:50% 50%;background-repeat:no-repeat;border:1px solid #eee;cursor:default!important}
.more::-moz-focus-inner{border:0}
.round{-moz-border-radius:8px;border-radius:8px}
#shareBtn{color:#666;display:block;height:45px;margin:0 auto;text-shadow:0 1px 0 #FFF;vertical-align:top;width:300px;line-height:1em}
</style>
<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.6.3.min.js"></script>
<script>
$(function(){leaveWord();
	$("#textbox").focus().bind("keyup","keydown",function(){leaveWord()});
});

function leaveWord(num){
	if (!num) num = 140;
	var leave = num-$("#textbox").val().length;
	if (leave < 0){
		$("#tip").html("<b>-" + (-leave) + "</b>");
	} else{
		$("#tip").html("<b>" + leave + "</b>");
		if (leave > 40){ 
			$("#tip, #tip b").css("color","#CCC");
		} else if(leave > 20){
			$("#tip, #tip b").css("color","#CAA");
		} else if(leave > 10){
			$("#tip, #tip b").css("color","#C88");
		} else if(leave > 0){
			$("#tip, #tip b").css("color","#C44");
		} else{
			$("#tip, #tip b").css("color","#E00");
		}
	}
}
</script>
</head>

<body>
<?php
	$t = getTwitter();
	if ( isset($_POST['status']) ){
		$status = $_POST['status'];
		if (mb_strlen($status,'utf-8') > 140){
			$status = mb_substr($status, 0, 140, 'utf-8');
		}
		$status .= $_POST['url'];
		$result = $t->update($status);
	}
	
	$text = '';
	
	if ( isset($_GET['u']) ){
		$url = $_GET['u'];
	}
	
	if ( isset($_GET['t']) ){
		$title = $_GET['t'];
		$text = $_GET['t'];
	}
	
	if ( isset($_GET['d']) ){
		$select = $_GET['d'];
		if ( trim($select) != "" ) $text = $select;
	}
	
	$text = $text;	
	$siteUrl = str_replace('share', 'index', 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER['PHP_SELF']);
	?>
<div id="share">

	<?php if ( !$t->username ){?>
		<div id="message">Please <a href="login.php" target="_blank">login</a> first.</div>
	<?php } else if ( isset($_POST['status']) ){ 
			if ($result){
	?>
				<div id="message">Successfully shared your stuff on Embr! <a href="javascript:window.close()">Close</a></div>
					<script type="text/javascript">
					setTimeout("window.close()",1000);
					</script>
		<?php } else{ ?>
				<div id="message">Failed to share your stuff, please try again. <a href="javascript:window.history.go(-1)">Go Back</a></div>
		<?php 
			}
	   } else{ 
	?>
		<form action="share.php" method="post">
		<table>
			<tr>
				<td colspan="2"><h2>Share to Embr</h2><span id="tip"><b>140</b></span></td>
			</tr>
			<tr>
				<td><input type="text" name="url" id="url" disabled="ture" value="<?php echo $url?>"/></td>
			</tr>
			<tr>
			<td><textarea name="status" id="textbox"><?php echo $text?> <?php if (strlen($url)>30) echo urlshorten($url); else echo $url ?></textarea></td>
			</tr>
			<tr>
			<td>
				<input class="more round" id="shareBtn" type="submit" value="Share" />
				</td>
			</tr>
		</table>
		</form>
	<?php } ?>
</div>
</body>
</html>
