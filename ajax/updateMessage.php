<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	include_once('../lib/timeline_format.php');
	$t = getTwitter();
	if ( isset($_GET['since_id']) ) {

		$messages = $t->directMessages(false, $_GET['since_id']);
		$count = count($messages);
		if ($count == 0) {
			echo "empty";
		} else {
			$output = '';
			foreach ($messages as $message) {
				$output .= format_message($message);
			}
			$tweetCounter = "<span class=\"tweetcount\">$count</span> unread message(s)";
			$output .= '<div class="new">'.$tweetCounter.'</div>';
			echo $output;
		}
	} else {
		echo 'error';
	}

?>
