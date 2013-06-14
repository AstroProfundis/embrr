<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	$ss = $t->savedSearches();
	$answer = '[';
	$firsts = true;

	foreach($ss as $onesearch){
		if (!$firsts){
			$answer .= ',';
		}
		else{
			$firsts = false;
		}
		$answer .= '"'.$onesearch->query.'"';
	}

	$answer .=']';
	echo $answer;
?>

