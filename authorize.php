<?php

	include ('lib/twitese.php');
	$url = 'https://api.twitter.com/oauth/authorize';
	echo processCurl($url, http_build_query($_POST));
?>