<?php 
	include ('../lib/twitese.php');
	if(!isset($_SESSION)){
		session_start();
	}
	$t = getTwitter();
	$limit = $t->ratelimit();
	$reset = intval((format_time($limit->reset_time) - time())/60);
	$remaining = $limit->remaining_hits < 0 ? 0 : $limit->remaining_hits;
	$hourly = $limit->hourly_limit;
	header('Content-Type: text/html');
	echo "<li><span style=\"color: #2276BB\">API: $remaining/$hourly</span></li>
	<li><span style=\"color: #2276BB\">Reset in $reset min(s)</span></li>";
?>
