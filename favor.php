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
	$p = 1;
	if (isset($_GET['p'])) {
		$p = (int) $_GET['p'];
		if ($p <= 0) $p = 1;
	}

	$statuses = $t->getFavorites($p);
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
