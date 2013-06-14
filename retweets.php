<?php
	include_once('lib/twitese.php');
	$title = "Retweets";
	include_once('inc/header.php');
	include_once('lib/timeline_format.php');
	if (!loginStatus()) header('location: login.php');

	$count = isset($_GET['count']) ? $_GET['count'] : 20;
	$since_id = isset($_GET['since_id']) ? $_GET['since_id'] : false;
	$max_id = isset($_GET['max_id']) ? $_GET['max_id'] : false;

	$t = getTwitter();

	$retweets = $t->retweets_of_me($count, $since_id, $max_id);

	echo '<div id="statuses" class="column round-left">';
	include_once('inc/sentForm.php');
	$html = '<script src="js/btns.js"></script>
	<style>
	.big-retweet-icon{display:none}
	</style>';
	$html .='<div class="clear"></div>';
	$empty = count($retweets) == 0? true: false;
	if ($empty) {
		$html .= "<div id=\"empty\">No retweets to display.</div>";
	} else {
		$html .= '<ol class="timeline" id="allTimeline">';
		$firstid = false;
		$lastid = false;
		foreach($retweets as $retweet){
			if (!$firstid) $firstid = $retweet->id_str;
			$lastid = $retweet->id_str;
			$html .= format_retweet_of_me($retweet);
		}
		$lastid = bcsub($lastid, "1");
		$html .= '</ol><div id="pagination">';
			$html .= "<a id=\"less\" class=\"round more\" style=\"float: left;\" href=\"retweets.php?since_id={$firstid}\">Back</a>";
			$html .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"retweets.php?max_id={$lastid}\">Next</a>";
		$html .= "</div>";
	}
	echo $html;
	include_once('inc/sidebar.php');
	include_once('inc/footer.php');
?>
