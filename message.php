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

	<?php if ( isset($_GET['id']) ) { ?>
	<h2>To <input type="text" style="border: 1px solid rgb(167, 166, 170); margin: 0px 0px 6px; padding: 2px; height: 14px; width: 120px; font-size: 13px;" name="sent_id" id="sent_id" value="<?php echo $_GET['id'] ?>"/></h2>
	<?php	} else { ?>
	<h2>To <input type="text" style="border: 1px solid rgb(167, 166, 170); margin: 0px 0px 6px; padding: 2px; height: 14px; width: 120px; font-size: 13px;" name="sent_id" id="sent_id" /></h2>
	<?php	} ?>
	
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
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		if ($isSentPage) {
			$messages = $t->sentDirectMessage($p);
		} else {
			$messages = $t->directMessages($p);
		}
		if ($messages === false) {
			header('location: error.php');exit();
		} 
		$empty = count($messages) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">No tweets to display.</div>";
		} else {
			include ('lib/timeline_format.php');
			$output = '<ol class="timeline" id="allMessage">';
			
			foreach ($messages as $message) {
				$output .= format_message($message,$isSentPage);
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			
			if ($isSentPage) {
				if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"message.php?t=sent&p=" . ($p-1) . "\">Back</a>";
				if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"message.php?t=sent&p=" . ($p+1) . "\">Next</a>";
			} else {
				if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"message.php?p=" . ($p-1) . "\">Back</a>";
				if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"message.php?p=" . ($p+1) . "\">Next</a>";
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
