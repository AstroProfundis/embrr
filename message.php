<?php
	include_once('lib/twitese.php');
	$title = 'Direct Messages';
	include_once('inc/header.php');
	if (!loginStatus()) header('location: login.php');
?>

<script src="js/message.js"></script>
<style>.timeline li{border-bottom:1px solid #EFEFEF;border-top:none !important}</style>

<?php 
	$isSentPage = isset($_GET['t']) ? true : false;
?>
<div id="statuses" class="column round-left">

	<h2>To <input type="text" style="border: 1px solid rgb(167, 166, 170); margin: 0px 0px 6px; padding: 2px; height: 14px; width: 120px; font-size: 13px;" name="sent_id" id="sent_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>"/></h2>
	
	<?php include('inc/sentForm.php')?>
	
	<div id="subnav">
	<?php if ($isSentPage) {?>
       	<span class="subnavLink"><a href="message.php">Inbox</a></span><span class="subnavNormal">Sent</span>
	<?php } else {?>
       	<span class="subnavNormal">Inbox</span><span class="subnavLink"><a href="message.php?t=sent">Sent</a></span>
	<?php } ?>
    </div>

	<?php 
		$t = getTwitter();
		$since_id = isset($_GET['since_id']) ? $_GET['since_id'] : false;
		$max_id = isset($_GET['max_id']) ? $_GET['max_id'] : false;
	
		if ($isSentPage) {
			$messages = $t->sentDirectMessages($since_id, $max_id);
		} else {
			$messages = $t->directMessages($since_id, $max_id);
		}
		if ($messages === false) {
			header('location: error.php?code='.$t->http_code);exit();
		}

		$count_t = count($messages);
		if ($count_t > 1) {
			$empty = 0; // 0 for not empty
		} else if ($count_t < 1) {
			$empty = 1; // 1 for no tweet to display
		} else {
			$empty = $t->http_code == 429 ? 2 : 0;
		}

		if ($empty == 1) {
			echo "<div id=\"empty\">No message to display.</div>";
		} else if ($empty == 2) {
			echo "<div id=\"empty\">API quota is used out, please wait for a moment before next refresh.</div>";
		} else {
			include ('lib/timeline_format.php');
			$output = '<ol class="timeline" id="allMessage">';
			
			foreach ($messages as $message) {
				$output .= format_message($message,$isSentPage);
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			$firstmsg = $messages[0]->id_str;
			$lastmsg = bcsub($messages[count($messages)-1]->id_str, "1");
			if ($isSentPage) {
				$output .= "<a id=\"less\" class=\"btn btn-white\" style=\"float: left;\" href=\"message.php?t=sent&since_id=" . $firstmsg . "\">Back</a>";
				$output .= "<a id=\"more\" class=\"btn btn-white\" style=\"float: right;\" href=\"message.php?t=sent&max_id=" . $lastmsg . "\">Next</a>";
			} else {
				$output .= "<a id=\"less\" class=\"btn btn-white\" style=\"float: left;\" href=\"message.php?since_id=" . $firstmsg ."\">Back</a>";
				$output .= "<a id=\"more\" class=\"btn btn-white\" style=\"float: right;\" href=\"message.php?max_id=" . $lastmsg ."\">Next</a>";
			}
			
			$output .= "</div>";	
			echo $output;
		}
	?>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
