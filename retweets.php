<?php
	include_once('lib/twitese.php');
	$title = "Retweets";
	include_once('inc/header.php');
	include_once('lib/timeline_format.php');
	if (!loginStatus()) header('location: login.php');

	$type = 'retweets';
	$page = isset($_GET['p']) ? $_GET['p'] : 1;
	$count = isset($_GET['count']) ? $_GET['count'] : 20;
	$since_id = isset($_GET['since']) ? $_GET['since'] : false;
	$max_id = isset($_GET['maxid']) ? $_GET['maxid'] : false;

	$t = getTwitter();
	$retweets = $t->retweets_of_me($page, $count, $since_id, $max_id);
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
		foreach($retweets as $retweet){
			$html .= format_retweet_of_me($retweet);
		}
		$html .= '</ol><div id="pagination">';
			if ($page >1) $html .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"retweets.php?p=" . ($page-1) . "\">Back</a>";
			if (!$empty) $html .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"retweets.php?p=" . ($page+1) . "\">Next</a>";
		$html .= "</div>";
	}
	echo $html;
	include_once('inc/sidebar.php');
	include_once('inc/footer.php');
?>
