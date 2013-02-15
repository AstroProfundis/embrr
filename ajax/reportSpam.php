<?php
	include_once("../lib/twitese.php");
	if(!isset($_POST['spammer'])){
		echo 'error';
		exit;
	}
	$spammer = trim($_POST['spammer']);
	if($spammer == ''){
		echo 'error';
		exit;
	}
	$t = getTwitter();
	$result = $t->reportSpam($spammer);
	if(isset($result->screen_name)){
		echo 'success';
	}else{
		echo 'error';
	}
?>
