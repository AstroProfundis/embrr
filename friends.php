<?php 
	include ('lib/twitese.php');
	$title = "Following";
	include ('inc/header.php');
	if (!loginStatus()) header('location: login.php');
	$type = 'friends';
	include ('inc/userlist.php');
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
