<?php 
	include ('lib/twitese.php');
	$title = "@{$_GET['id']} - Followers";
	include ('inc/header.php');
	
	$type = 'list_followers';
	include ('inc/userlist.php');
	
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
