<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	include_once('../lib/timeline_format.php');
	$t = getTwitter();
	if ( isset($_GET['since_id']) ) {

		$statuses = $t->replies(false, $_GET['since_id']);
		$count = count($statuses);
		if ($count == 0) {
			echo "empty";
		} else {
			$output = "";
			foreach($statuses as $status) {
				$output .= format_timeline($status, $t->username);
			}
			$tweetCounter = "<span class=\"tweetcount\">$count</span> unread mention(s)";
			$output .= '<div class="new">'.$tweetCounter.'</div>';
			echo $output;
		}

	} else {
		echo 'error';
	}

?>
