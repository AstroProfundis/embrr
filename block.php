<?php
include('lib/twitese.php');
$title = "Blocking";
include('inc/header.php');
if(!loginStatus()){
	header('location: login.php');
}
$type = 'blocks';
include('inc/userlist.php');
include('inc/sidebar.php');
include('inc/footer.php');
?>
