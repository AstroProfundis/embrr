<?php
	include ('lib/twitese.php');
	$title = "Replies";
	include ('inc/header.php');
	if (!loginStatus()) header('location: login.php');
?>

<script src="js/btns.js"></script>

<div id="statuses" class="column round-left">
<?php 
	include('inc/sentForm.php');
	include('lib/timeline_format.php');
		$t = getTwitter();
		$since_id = isset($_GET['since_id']) ? $_GET['since_id'] : false;
		$max_id = isset($_GET['max_id']) ? $_GET['max_id'] : false;
	
		$statuses = $t->replies($since_id, $max_id);
		if ($statuses === false) {
			header('location: error.php');exit();
		} 

		$count_t = count($messages);
        if ($count_t > 1) {
            $empty = 0;
        } else if ($count_t < 1) {
            $empty = 1;
        } else {
            $api_quota = get_object_vars($t->ratelimit()->resources->statuses);
            $empty = $api_quota['/statuses/mentions_timeline']->remaining == 0 ? 2 : 0;
        }
		if ($empty) { //TODO: show different message for API outage and no tweets
			echo "<div id=\"empty\">No tweet to display.<br />Check API quota to see if you used it out.</div>";
		} else {
			$output = '<ol class="timeline" id="allTimeline">';
			
			$firstid = false;
			$lastid = false;
			foreach ($statuses as $status) {
				if (!$firstid) $firstid = $status->id_str;
				$lastid = $status->id_str;
				$output .= format_timeline($status,$t->username);
			}
			$lastid = bcsub($lastid, "1");
			
			$output .= "</ol><div id=\"pagination\">";
			
			$output .= "<a id=\"less\" class=\"round more\" style=\"float: left;\" href=\"replies.php?since_id=" . $firstid . "\">Back</a>";
			$output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"replies.php?max_id=" . $lastid . "\">Next</a>";
			
			$output .= "</div>";
			
			echo $output;
		}
	?>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
