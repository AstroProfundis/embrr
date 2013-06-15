<?php
	include ('lib/twitese.php');
	$title = 'Home';
	include ('inc/header.php');

	if (!loginStatus()) header('location: login.php'); 
	$t = getTwitter();
	if (isset($_POST['status']) && isset($_POST['in_reply_to'])) {
		if (trim($_POST['status']) !== '')
		{
			$result = $t->update($_POST['status'], $_POST['in_reply_to']);
			if ($result)
			{
				$user = $result->user;
				$time = $_SERVER['REQUEST_TIME']+3600*24*365;
				if ($user)
				{
					setcookie('friends_count', $user->friends_count, $time, '/');
					setcookie('statuses_count', $user->statuses_count, $time, '/');
					setcookie('followers_count', $user->followers_count, $time, '/');
					setcookie('imgurl', getAvatar($user->profile_image_url), $time, '/');
					setcookie('name', $user->name, $time, '/');
				}
			}
		}
		header('location: index.php');
	}
?>
<script src="js/home.js"></script>
<div id="statuses" class="column round-left">
<?php
  include('inc/sentForm.php'); 
  
	$since_id = isset($_GET['since_id']) ? $_GET['since_id'] : false;
	$max_id = isset($_GET['max_id']) ? $_GET['max_id'] : false;

	$statuses = $t->homeTimeline($since_id, $max_id);
	if ($statuses == false)
	{
		header('location: error.php');exit();
	}
	$count = count($statuses);
	$empty = $count == 0 ? true: false;
	if ($empty)
	{
		echo "<div id=\"empty\">No tweet to display.</div>";
	}
	else
	{
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

		$output .= "<a id=\"less\" class=\"round more\" style=\"float: left;\" href=\"index.php?since_id=" . $firstid . "\">Back</a>";
		$output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"index.php?max_id=" . $lastid . "\">Next</a>";
		$output .= "</div>";
		echo $output;
	}
?>
</div>
<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
