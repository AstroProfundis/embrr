<?php
	include ('lib/twitese.php');
	$title = 'Home';
	include ('inc/header.php');

	if (!loginStatus()) header('location: login.php'); 
?>
<script src="js/home.js"></script>
<div id="statuses" class="column round-left">
<?php
  include('inc/sentForm.php'); 
  
	$since_id = isset($_GET['since_id']) ? $_GET['since_id'] : false;
	$max_id = isset($_GET['max_id']) ? $_GET['max_id'] : false;

	$t = getTwitter();
	$statuses = $t->homeTimeline($since_id, $max_id);
	if ($statuses == false)
	{
		header('location: error.php?code='.$t->http_code);exit();
	}
	$count = count($statuses);
	$empty = $count == 0 ? true : false;
	if ($empty)
	{
		echo "<div id=\"empty\">No tweet to display.</div>";
	} else if ($t->http_code == 429) {
		echo "<div id=\"empty\">API quota is used out, please wait for a moment before next refresh.</div>";
	} else {
		$output = '<ol class="timeline" id="allTimeline">';

		include('lib/timeline_format.php');
		$maxid = isset($_COOKIE['maxid']) ? $_COOKIE['maxid'] : '';
		$firstid = false;
		$lastid = false;
		foreach ($statuses as $status) {
			if (!$firstid) $firstid = $status->id_str;
			$lastid = $status->id_str;
			if($maxid == '' || $p == 1 || strcmp($status->id_str,$maxid) < 0) {
				if (isset($status->retweeted_status)) {
					$output .= format_retweet($status);
				} else { 
					$output .= format_timeline($status,$t->username);
				}
			}
		}
		$lastid = bcsub($lastid, "1");

		$output .= "</ol><div id=\"pagination\">";
		$time = $_SERVER['REQUEST_TIME']+3600;
		setcookie('maxid',$statuses[$count-1]->id_str,$time,'/');

		$output .= "<a id=\"less\" class=\"btn btn-white\" style=\"float: left;\" href=\"index.php?since_id=" . $firstid . "\">Back</a>";
		$output .= "<a id=\"more\" class=\"btn btn-white\" style=\"float: right;\" href=\"index.php?max_id=" . $lastid . "\">Next</a>";
		$output .= "</div>";
		echo $output;
	}
?>
</div>
<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
