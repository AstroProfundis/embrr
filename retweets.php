<?php
	include_once('lib/twitese.php');
	$title = "Retweets";
	include_once('inc/header.php');
	include_once('lib/timeline_format.php');
	if (!loginStatus()) header('location: login.php');

	$type = 'retweets';
	$retweetsType = isset($_GET['type']) ? $_GET['type'] : 'to';
	$page = isset($_GET['p']) ? $_GET['p'] : 1;
	$count = isset($_GET['count']) ? $_GET['count'] : 20;
	$since_id = isset($_GET['since']) ? $_GET['since'] : false;
	$max_id = isset($_GET['maxid']) ? $_GET['maxid'] : false;

	$t = getTwitter();
	$retweets_to_me_class = '';
	$retweeted_by_me_class = '';
	$retweeted_of_me_class = '';
	$retweets;

	$retweets_to_me_class = 'subnavLink';
	$retweeted_by_me_class = 'subnavLink';
	$retweeted_of_me_class = 'subnavNormal';
	$retweets = $t->retweets_of_me($count, $since_id, $max_id);

	echo '<div id="statuses" class="column round-left">';
	include_once('inc/sentForm.php');
	$html = '<script src="js/btns.js"></script>
	<style>
	.big-retweet-icon{display:none}
	.timeline li {border-bottom:1px solid #EFEFEF;border-top:none !important}
	</style>';
	$html .= "<div id='subnav'>
		<a href='retweets.php?type=mine'><span class='$retweeted_of_me_class'>Your tweets, retweeted</span></a>
		</div>";
	$html .='<div class="clear"></div>';
	$empty = count($retweets) == 0? true: false;
	if ($empty) {
		$html .= "<div id=\"empty\">No retweets to display.</div>";
	} else {
		$html .= '<ol class="timeline" id="allTimeline">';
		$since_id = $retweets->id;
		foreach($retweets as $retweet){
			$html .= format_retweet_of_me($retweet);
			$max_id = $retweet->id;
		}
		$html .= '</ol><div id="pagination">';
			if ($page >1) $html .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"retweets.php?type=".$retweetsType."&p=" . ($page-1) . "."&max_id=<?php echo $since_id?>\">Back</a>";
			if (!$empty) $html .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"retweets.php?type=".$retweetsType."&p=" . ($page+1) . "."&max_id=<?php echo $max_id?>\">Next</a>";
		$html .= "</div>";
	}
	echo $html;
	include_once('inc/sidebar.php');
	include_once('inc/footer.php');
?>
