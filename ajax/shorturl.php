<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include_once('../lib/twitese.php');
	$long_urls = array();
	$long_urls = explode("|",substr($_POST['long_urls'],0,-1));
	$short_urls ='';
	foreach($long_urls as $url){
		$short_urls .= urlshorten($url).'|'.$url.'^';
	}
	echo substr($short_urls,0,-1);
?>
