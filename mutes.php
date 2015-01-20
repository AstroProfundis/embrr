<?php 
	include ('lib/twitese.php');
	$title = "Mutes";
	include ('inc/header.php');
	if (!loginStatus()) header('location: login.php');
	
	$type = 'mutes';
	include ('inc/userlist.php');
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
