<?php 
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	$result = $t->makeFavorite($_POST['status_id']);
	if ($result) echo 'success';
	else echo 'error';
?>

