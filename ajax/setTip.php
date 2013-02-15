<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	if(trim(SECURE_KEY) == "") {
		echo 'unsecured';
	} else {
		//header('location: ../pr.php?pr='.urlencode(print_r($_POST,true)));
		if(isset($_POST)){
			if($_POST['reset'] == 'true') {
				delCookie('Tip_Title');
				delCookie('Tip_Content');
				delCookie('Tip_More');
				echo 'reset';exit();
			}
			$time = $_SERVER['REQUEST_TIME'] + 3600*24*365;
			setEncryptCookie('Tip_Title',$_POST['Tip_Title'],$time);
			setEncryptCookie('Tip_Content',$_POST['Tip_Content'],$time);
			setEncryptCookie('Tip_More',$_POST['Tip_More'],$time);
			echo 'success';
		} else {
			echo 'error';
		}
	}
?>