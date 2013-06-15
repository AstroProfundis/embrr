<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	if ($_GET['method'] == "list") {
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
			$answer .= '["'.$onesearch->id_str.'", "'.$onesearch->query.'"]';
		}

		$answer .=']';
		echo $answer;
	}
	else if ($_GET['method'] == "delete") {
		$ssid = $_GET['ssid'];
		$dss = $t->deleteSavedSearch($ssid);
		if (isset($dss->query))
			echo "success";
		else
			echo "error";
	}
?>

