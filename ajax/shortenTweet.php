<?php
	include_once('../lib/twitese.php');
	if(!isset($_POST['text'])){
		echo 'error';
		exit;
	}
	$text = $_POST['text'];
	if(strlen($text) == 0){
		echo 'error';
		exit;
	}
	$shorten_api = 'http://tweetshrink.com/shrink?format=xml&text=';
	$request = $shorten_api.rawurlencode($text);
	try{
		$obj = objectifyXml(processCurl($request));
		if (isset($obj->text)){
			echo $obj->text;
		}else{
			echo 'error';
		}
	}catch(Exception $e){
		echo 'error';
	}
?>
