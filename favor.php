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
	$max_id = isset($_GET['max_id']) ? $_GET['max_id'] : false;

	$statuses = $t->getFavorites($user_id, $since_id, $max_id);
	if ($statuses === false) {
		header('location: error.php');exit();
	} 
	$empty = count($statuses) == 0 ? true : false;
	if ($empty) {
		echo "<div id=\"empty\">No tweet to display.</div>";
	} else if ($t->http_code == 429) {
		echo "<div id=\"empty\">API quota is used out, please wait for a moment before next refresh.</div>";
	} else {
		$output = '<ol class="timeline" id="allTimeline">';
		include('lib/timeline_format.php');
		$firstid = false;
		$lastid = false;
		foreach ($statuses as $status) {
			if (isset($status->retweeted_status)) {
				$output .= format_retweet($status);
			} else { 
				$output .= format_timeline($status,$t->username);
			}
			if(!$firstid)
				$firstid = $status->id_str;
			$lastid = $status->id_str;
		}
		$lastid = bcsub($lastid, "1");

		$output .= "</ol><div id=\"pagination\">";

		$output .= "<a id=\"less\" class=\"round more\" style=\"float: left;\" href=\"favor.php?since_id={$firstid}\">Back</a>";
		$output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"favor.php?max_id={$lastid}\">Next</a>";

		$output .= "</div>";

		echo $output;
	}
?>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
