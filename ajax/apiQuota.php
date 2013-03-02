<?php 
	include ('../lib/twitese.php');
	if(!isset($_SESSION)){
		session_start();
	}
	$t = getTwitter();
	$limit = get_object_vars($t->ratelimit()->resources->statuses);
	$timeline_limit = $limit["/statuses/home_timeline"];
	$mentions_limit = $limit['/statuses/mentions_timeline'];
	$timeline_reset = intval($timeline_limit->reset - $_SERVER['REQUEST_TIME']);
	$mentions_reset = intval($mentions_limit->reset - $_SERVER['REQUEST_TIME']);
	$timeline_remaining = $timeline_limit->remaining < 0 ? 0 : $timeline_limit->remaining;
	$mentions_remaining = $mentions_limit->remaining < 0 ? 0 : $mentions_limit->remaining;
	$timeline_qlimit = $timeline_limit->limit;
	$mentions_qlimit = $mentions_limit->limit;
	header('Content-Type: text/html');
	echo "<li><span style=\"color: #2276BB\">Timeline API remains: $timeline_remaining/$timeline_qlimit</span></li>
	<li><span style=\"color: #2276BB\">Reset in $timeline_reset sec(s)</span></li>
	<li><span style=\"color: #2276BB\">Mentions API remains: $mentions_remaining/$mentions_qlimit</span></li>
	<li><span style=\"color: #2276BB\">Reset in $mentions_reset sec(s)</span></li>";
?>
