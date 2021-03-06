<script src="js/formfunc.js"></script>
<?php if (!isset($_sentText)) { 
	if ($title != 'Direct Messages') {
	echo "<h2>What's happening?</h2>" ;
	} ?> 
<span id="tip"><span class="fa fa-spinner fa-spin" style="display:none"></span><b>140</b></span>
<?php } ?>
<form id="photoArea">
<span style="font-weight: bold;">Upload Image</span>
<input type="file" name="image" id="imageFile"/> 
<input type="button" id="imageUploadSubmit" class="btn" value="Upload"/>
<ul id="media_preview"></ul>
<a href="#" onclick="$('#photoArea').slideToggle(300);return false;" title="Close" class="close fa fa-times"></a>
</form>

<form id="filterArea">
<span style="font-weight: bold;">Filter Timeline</span>
<p>Seperate keywords with comma. [eg: twitter,hello] Also usernames <b>without</b> @</p> 
<input type="text" id="iptFilter" name="iptFilter" class="filter_input"/>
<input type="submit" style="vertical-align: top; padding: 5px; margin: 9px 3px 0pt 6px;" id="filterSubmit" class="btn" value="Update">
<input type="submit" style="padding: 5px; vertical-align: top; margin-top: 9px;" id="filterReset" class="btn" value="Reset">
<input type="submit" style="padding: 5px; vertical-align: top; margin: 9px 0pt 0pt 3px;" id="filterHide" class="btn" value="Hide @">
<a class="close fa fa-times" title="Close" onclick="$('#filterArea').slideToggle(300);return false;" href="#"></a>
</form>

<form id="symArea">
<div id="symbols">
<?php include ('inc/symbols.inc');?>
</div>
<a class="close fa fa-times" title="Close" onclick="$('#symArea').slideToggle(300);return false;" href="#"></a>
</form>

<form>
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
			header('location: error.php?code='.$t->http_code);exit();
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
	<a class="a-btn btn-disabled" id="tweeting_button" tabindex="2" href="#" title="Ctrl/⌘+Enter also works!"><span class="fa fa-send-o"></span></a>
</div>
	
	<div id="func_set" style="left:<?php echo ($title == 'Updates' || $title == 'Home') ? '271' : '298'; ?>px">
	
	<a class="func_btn fa fa-link" href="javascript:shortUrlDisplay();" title="Shorten URL"></a>
	
	<a class="func_btn fa fa-image" title="Upload Image" id="photoBtn"></a>
	
	<a class="func_btn fa fa-filter" id="filterBtn" title="Filter Timeline"></a>
	
	<a class="func_btn fa fa-heart" id="symbolBtn" title="Symbols and smileys"></a>

	<a class="func_btn fa fa-reply" id="restoreBtn" title="Restore previous tweet"></a>
	
	<?php if($title == 'Updates' || $title == 'Home') { ?>
	<a class="func_btn fa fa-pause" id="autoBtn" title="Auto refresh control"></a>
	<?php } ?>
	
	<a class="func_btn fa fa-power-off" id="clearBtn" title="Sweep Timeline"></a>

	<a class="func_btn fa fa-refresh" id="refreshBtn" title="Refresh the timeline"></a>
	</div>
	</form>
	<div class="clear"></div>
