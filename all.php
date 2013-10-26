<?php 
	include_once('lib/twitese.php');
	$title = 'Updates';
	include_once('inc/header.php');
	include_once('lib/timeline_format.php');
	if (!loginStatus()) header('location: login.php');
?>
<div id="statuses" class="column round-left">
<?php
	include('inc/sentForm.php');
?>
<script src="js/all.js"></script>
<style>.timeline li {border-bottom:1px solid #EFEFEF;border-top:none !important}</style>
			<div id="allNav">
			<a class="allBtn allHighLight" id="allTimelineBtn" href="#">Updates</a>
			<a class="allBtn" id="allRepliesBtn" href="#">Replies</a>
			<a class="allBtn" id="allMessageBtn" href="#">Messages</a>
		</div>
<?php
	$statuses = $t->homeTimeline();
	if ($statuses === false) {
		header('location: error.php');exit();
	}
	$empty = count($statuses) == 0? true: false;
	if ($empty) {
		echo "<div id=\"empty\">No tweet to display</div>";
	} else if ($t->http_code == 429) {
		echo "<div id=\"empty\">API quota is used out, please wait for a moment before next refresh.</div>";
	} else {
		$output = '<ol class="timeline" id="allTimeline">';
		foreach ($statuses as $status) {
			if(isset($status->retweeted_status)){
				$output .= format_retweet($status);
			}else{
				$output .= format_timeline($status, $t->username);
			}
		}

		$output .= "</ol>";

		echo $output;
	}

	$statuses = $t->replies();
	if ($statuses === false) {
		header('location: error.php');exit();
	}
	$empty = count($statuses) == 0? true: false;
	if ($empty) {
		echo "<div id=\"empty\">No tweet to display</div>";
	} else if ($t->http_code == 429) {
		echo "<div id=\"empty\">API quota is used out, please wait for a moment before next refresh.</div>";
	} else {
		$output = '<ol class="timeline" id="allReplies">';

		foreach ($statuses as $status) {
			$output .= format_timeline($status, $t->username);
		}

		$output .= "</ol>";

		echo $output;
	}


	$messages = $t->directMessages();
	if ($messages === false) {
		header('location: error.php');exit();
	}
	$empty = count($messages) == 0? true: false;
	if ($empty) {
		echo "<div id=\"empty\">No tweet to display</div>";
	} else if ($t->http_code == 429) {
		echo "<div id=\"empty\">API quota is used out, please wait for a moment before next refresh.</div>";
	} else {
		$output = '<ol class="timeline" id="allMessage">';

		foreach ($messages as $message) {
			$output .= format_message($message);
		}

		$output .= "</ol>";
		echo $output;
	}
?>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
