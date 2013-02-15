<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	$t->debug = true;
	if ( isset($_POST['action']) && isset($_POST['id']) ) {
		if ($_POST['action'] == 'create') {
			$result = $t->followList($_POST['id']);
			if ($result) echo 'success';
			else echo 'error';
		} else if ($_POST['action'] == 'destory') {
			$result = $t->unfollowList($_POST['id']);
			if ($result) echo 'success';
			else echo 'error';
		} 
	}
	
?>

