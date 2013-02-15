<script src="js/formfunc.js"></script>
<?php if (!isset($_sentText)) { 
	if ($title != 'Direct Messages') {
	echo "<h2>What's happening?</h2>" ;
	} ?> 
<span id="tip"><b>140</b></span>
<?php } ?>
<form enctype="multipart/form-data" action="ajax/uploadImage.php?do=image" method="post" id="photoArea">
<span style="font-weight: bold;">Upload Image</span>
<p>Powered by Img.ly</p>
<input type="file" name="image" id="imageFile"/> 
<input type="submit" id="imageUploadSubmit" class="btn" value="Upload"/>
<a href="#" onclick="$('#photoArea').slideToggle(300)" title="Close" class="close"></a>
</form>

<form id="filterArea">
<span style="font-weight: bold;">Filter Timeline</span>
<p>Seperate keywords with comma. [eg: twitter,hello] Also usernames <b>without</b> @</p> 
<input type="text" id="iptFilter" name="iptFilter" class="filter_input"/>
<input type="submit" style="vertical-align: top; padding: 5px; margin: 9px 3px 0pt 6px;" id="filterSubmit" class="btn" value="Update">
<input type="submit" style="padding: 5px; vertical-align: top; margin-top: 9px;" id="filterReset" class="btn" value="Reset">
<input type="submit" style="padding: 5px; vertical-align: top; margin: 9px 0pt 0pt 3px;" id="filterHide" class="btn" value="Hide @">
<a class="close" title="Close" onclick="$('#filterArea').slideToggle(300)" href="#"></a>
</form>

<form id="symArea">
<div id="symbols">
<?php include ('inc/symbols.inc');?>
</div>
<a class="close" title="Close" onclick="$('#symArea').slideToggle(300)" href="#"></a>
</form>

<form id="transArea">
<span style="font-weight: bold; display: block; margin-bottom: 5px;">Translation Settings</span>
<p>Translate tweets into
<select name="langs" style="border: 1px solid rgb(170, 170, 170); padding: 1px 2px;">
<option value="ar">Arabic</option>
<option value="zh-CN">简体中文</option>
<option value="zh-TW">繁體中文</option>
<option value="da">Danish</option>
<option value="nl">Dutch</option>
<option value="en">English</option>
<option value="fi">Finnish</option>
<option value="fr">French</option>
<option value="de">German</option>
<option value="el">Greek</option>
<option value="hu">Hungarian</option>
<option value="is">Icelandic</option>
<option value="it">Italian</option>
<option value="ja">Japanese</option>
<option value="ko">Korean</option>
<option value="lt">Lithuanian</option>
<option value="no">Norwegian</option>
<option value="pl">Polish</option>
<option value="pt">Portuguese</option>
<option value="ru">Russian</option>
<option value="es">Spanish</option>
<option value="sv">Swedish</option>
<option value="th">Thai</option>
</select>
</p>
<p>Translate my tweets into <select name="myLangs" style="border: 1px solid rgb(170, 170, 170); margin-top: 5px; padding: 1px 2px;">
<option value="ar">Arabic</option>
<option value="zh-CN">简体中文</option>
<option value="zh-TW">繁體中文</option>
<option value="da">Danish</option>
<option value="nl">Dutch</option>
<option value="en">English</option>
<option value="fi">Finnish</option>
<option value="fr">French</option>
<option value="de">German</option>
<option value="el">Greek</option>
<option value="hu">Hungarian</option>
<option value="is">Icelandic</option>
<option value="it">Italian</option>
<option value="ja">Japanese</option>
<option value="ko">Korean</option>
<option value="lt">Lithuanian</option>
<option value="no">Norwegian</option>
<option value="pl">Polish</option>
<option value="pt">Portuguese</option>
<option value="ru">Russian</option>
<option value="es">Spanish</option>
<option value="sv">Swedish</option>
<option value="th">Thai</option>
</select>
<input type="button" value="Translate" class="btn" id="translateMy" style="vertical-align: middle; padding: 3px 8px; margin-top: -3px;">
</p>
<a class="close" title="Close" onclick="$('#transArea').slideToggle(300)" href="#"></a>
</form>

<form action="index.php" method="post">
<a id="transRecover">Restore</a>
<textarea name="status" id="textbox"><?php if (isset($_sentText)) echo $_sentText ?></textarea>
<input type="hidden" id="in_reply_to" name="in_reply_to" value="<?php echo isset($_sentInReplyTo) ? $_sentInReplyTo : 0 ?>" />
<?php
	$p = 1;
	if (isset($_GET['p']))
	{
		$p = (int) $_GET['p'];
		if ($p <= 0) $p = 1;
	}
	if($_COOKIE['autoscroll'] == 'false' || $p == 1) {
		$t = getTwitter();
		$user = $t->veverify();
		if ($user === false) {
			header('location: error.php');exit();
		} 
		$empty = count($user) == 0 || !isset($user->status) || $user->status->text == '';
		if ($empty) {
			echo "<div id=\"currently\">
				<span id=\"full_status\"><strong >Latest:</strong></span>
				<span id=\"latest_status\">
				<span id=\"latest_text\">
				<span class=\"status-text\">What's happening?</span>
				<span class=\"full-text\" style=\"display:none\">What's happening?</span>
				<span class=\"entry-meta\" id=\"latest_meta\"></span>
				<span class=\"entry-meta\" id=\"full_meta\"></span>
				</span>
				</span>
				</div>";
		} else {
				$status = $user->status;
				$date = format_time($status->created_at);
				$text = formatText($status->text);
				$output = "
					<div id=\"currently\">
					<span id=\"full_status\" title=\"Click to view the full tweet\"><strong>Latest:</strong></span>
					<span id=\"latest_status\">
					<span id=\"latest_text\">
					<span class=\"status-text\">" . $text . "</span>
					<span class=\"full-text\" style=\"display:none\">" . $text . "</span>
					<span class=\"entry-meta\" id=\"latest_meta\"><a href=\"status.php?id=$status->id_str\" id=\"$date\" target=\"_blank\">".date('Y-m-d H:i:s', $date)."</a></span>
					<span class=\"entry-meta\" id=\"full_meta\" style=\"display:none\"><a href=\"status.php?id=$status->id_str\" id=\"$date\" target=\"_blank\">".date('Y-m-d H:i:s', $date)."</a></span>
					</span>
					</span>
					</div>
					";
			echo $output;
		}
	}
?>
<div id="tweeting_controls">
	<a class="a-btn a-btn-m btn-disabled" id="tweeting_button" tabindex="2" href="#" title="Ctrl/⌘+Enter also works!"><span>
		<?php if($title == 'Direct Messages') {
			echo 'Send';
			} else {
				echo 'Tweet';
			} ?>
		</span></a>
	</div>
	
	<div id="func_set">
	
	<a class="func_btn" href="javascript:shortUrlDisplay();" title="Shorten URL" style="background-position:-238px -113px">Shorten URL</a>
	
	<a class="func_btn" href="javascript:shortenTweet();" title="Shorten Tweet" style="background-position:-222px -48px;">Shorten Tweet</a>
	
	<a id="transBtn" title="Translation Settings" class="func_btn" style="background-position:-110px -80px;">Translate</a>
	
	<a title="Upload Image" id="photoBtn" class="func_btn" style="background-position: -207px -128px;">Image</a>
	
	<a id="filterBtn" title="Filter Timeline" class="func_btn" style="background-position:-174px -112px;">Filter</a>
	
	<a title="Sogou Cloud IME" href="javascript:void((function(){var%20n=navigator.userAgent.toLowerCase();ie=n.indexOf('msie')!=-1?1:0;if(document.documentMode)ie=0;charset='';if(ie)charset=document.charset;src=ie&amp;&amp;charset=='utf-8'?'http://web.pinyin.sogou.com/web_ime/init2_utf8.php':'http://web.pinyin.sogou.com/web_ime/init2.php';element=document.createElement('script');element.setAttribute('src',src);document.body.appendChild(element);})())" onclick="updateSentTip('Loading...', 5000, 'ing')" class="func_btn" style="background-position: -62px -112px;">Sogou</a>
	
	<a id="symbolBtn" title="Symbols and smileys" class="func_btn" style="background-position: -206px -113px;">Symbols</a>

	<a id="restoreBtn" style="background-position: 2px -64px;" class="func_btn" title="Restore previous tweet">Restore</a>
	
	<a id="autoBtn" title="Auto refresh control" class="func_btn pause">Pause</a>
	
	<a id="clearBtn" style="background-position: 3px -176px;" class="func_btn" title="Sweep Timeline" class="func_btn">Sweep</a>

	<a id="refreshBtn" title="Refresh the timeline" class="func_btn" style="background-position: -62px -80px;">Refresh</a>
	</div>
	</form>
	<div class="clear"></div>   