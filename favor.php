<?php 
	include ('lib/twitese.php');
	$title = "My Favorites";
	include ('inc/header.php');
	if (!loginStatus()) header('location: login.php');
?>

<script src="js/btns.js"></script>

<div id="statuses" class="column round-left">

	<?php include('inc/sentForm.php')?>

<?php 
	$t = getTwitter();
	$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : false;
	$since_id = isset($_GET['since_id']) ? $_GET['since_id'] : false;

	$FAV_COUNT = 50;
	$statuses = $t->getFavorites($user_id, $since_id, $FAV_COUNT); // due to the API change and its limits, only get 50 neweast favs and no pages supported
	if ($statuses === false) {
		header('location: error.php');exit();
	} 
	$empty = count($statuses) == 0? true: false;
	if ($empty) {
		echo "<div id=\"empty\">No tweet to display.</div>";
	} else {
		$output = '<ol class="timeline" id="allTimeline">';
		include('lib/timeline_format.php');
		foreach ($statuses as $status) {
			if (isset($status->retweeted_status)) {
				$output .= format_retweet($status);
			} else { 
				$output .= format_timeline($status,$t->username);
			}
		}

		$output .= "</ol><div id=\"pagination\">";

		if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"favor.php?p=" . ($p-1) . "\">Back</a>";
		if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"favor.php?p=" . ($p+1) . "\">Next</a>";

		$output .= "</div>";

		echo $output;
	}
?>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
