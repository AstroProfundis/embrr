<?php

function expandRedirect($shorturl, &$hops) {
	if (count($hops) >= 10) {
		return false;
	}
	$head = get_headers($shorturl, TRUE);
	if (!isset($head['Location']) || empty($head['Location'])) {
		return $shorturl;
	}
	$prevhop = $shorturl;
	foreach((array)$head['Location'] as $redir) {
		if (substr($redir, 0, 1)=='/' || preg_match('/[\.\/]'.preg_quote(parse_url($prevhop, PHP_URL_HOST)).'$/', parse_url($redir, PHP_URL_HOST))) {
			return $prevhop;
		}
		$hops[] = $prevhop;
		$prevhop = $redir;
	}
	return expandRedirect($redir, $hops);
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
