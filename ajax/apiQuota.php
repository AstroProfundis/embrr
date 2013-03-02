<?php 
	include ('../lib/twitese.php');
	if(!isset($_SESSION)){
		session_start();
	}
	$t = getTwitter();
	$limit = get_object_vars($t->ratelimit()->resources->statuses);
	$limit = $limit["/statuses/home_timeline"];
	$reset = intval($limit->reset - $_SERVER['REQUEST_TIME']);
	$remaining = $limit->remaining < 0 ? 0 : $limit->remaining;
	$qlimit = $limit->limit;
	header('Content-Type: text/html');
	echo "<li><span style=\"color: #2276BB\">API: $remaining/$qlimit</span></li>
	<li><span style=\"color: #2276BB\">Reset in $reset sec(s)</span></li>";
?>
