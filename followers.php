<?php 
	include ('lib/twitese.php');
	$title = "Followers";
	include ('inc/header.php');
	if (!loginStatus()) header('location: login.php');
	
	$type = 'followers';
	include ('inc/userlist.php');
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
