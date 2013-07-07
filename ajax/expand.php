<?php

include_once('../lib/config.php');
include_once('../lib/twitese.php');

if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != parse_url(BASE_URL, PHP_URL_HOST)) {
	echo '{"error":"Invalid referer."}';
	exit();
}

$url = isset($_GET['url']) ? $_GET['url'] : false;
if (!$url || empty($url)) {
	echo '{"error":"No URL is provided."}';
	exit();
}
if (!filter_var($url, FILTER_VALIDATE_URL)) {
	echo '{"error":"Malformed URL."}';
	exit();
}

$thehops = array();
$answer = expandRedirect($url, $thehops);
if (!$answer) {
	echo '{"error":"No URL is provided."}';
}
else
	echo "{\"expanded_url\":\"$answer\"}";

?>
